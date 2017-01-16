<?php namespace module\material\controller;

use houdunwang\request\Request;
use module\HdController;
use module\material\model\Material;
use system\model\Member;

/**
 * 微信素材管理
 * Class site
 * @package module\material
 * @author 向军
 */
class Site extends HdController {
	/**
	 * 上传素材
	 */
	public function upload_material() {
		$file = Request::post( 'file' );
		if ( ! is_file( $file ) ) {
			message( '文件不存在', '', 'error' );
		}
		$type = $_POST['type'];
		$data = \WeChat::instance( 'material' )->upload( $type, $file, 0 );
		if ( isset( $data['errcode'] ) ) {
			message( $data['errmsg'], '', 'error' );
		} else {
			$model             = new Material();
			$model['type']     = $type;
			$model['file']     = $_POST['file'];
			$model['media_id'] = $data['media_id'];
			$model['url']      = $data['url'];
			$model['status']   = 1;
			$model->save();
			message( '保存成功', '', 'success' );
		}
	}

	//删除素材
	public function delMaterial() {
		$model = Material::find( Request::post( 'id' ) );
		$data  = \WeChat::instance( 'material' )->delete( $model['media_id'] );
		if ( isset( $data['errcode'] ) ) {
			message( $data['errmsg'], '', 'error' );
		} else {
			$model->destory();
			message( '素材删除成功', '', 'success' );
		}
	}

	//图片
	public function image() {
		$data = Material::orderBy( 'id', 'DESC' )->paginate( 20, 8 );

		return view( $this->template . '/image.html' )->with( [ 'data' => $data ] );
	}

	//语音
	public function voice() {
		return view( $this->template . '/voice.html' );
	}

	//视频
	public function video() {
		return view( $this->template . '/video.html' );
	}

	//图文
	public function news() {
		$model           = new Member();
		$model['openid'] = 'aa';
		$model->save();
		exit;
		p( v( 'site' ) );
		$data = Material::where( 'siteid', SITEID )->where( 'type', 'news' )->orderBy( 'id', 'DESC' )->get();
		foreach ( (array) $data as $k => $v ) {
			$data[ $k ]['data'] = json_decode( $v['data'], true );
		}
		View::with( 'data', json_encode( $data, JSON_UNESCAPED_UNICODE ) );

		return view( $this->template . '/news.html' );
	}

	//删除图文
	public function delNews() {
		if ( IS_POST ) {
			$data = Material::find( Request::post( 'id' ) );
			if ( ! $data ) {
				message( '图文消息不存在', '', 'error' );
			}
			$result = WeChat::instance( 'material' )->delete( $data['media_id'] );
			if ( $result['errcode'] == 0 ) {
				Material::where( 'id', $data['id'] )->delete();
				message( '图文消息删除成功', '', 'success' );
			}
			message( "图文消息删除失败," . $result['errmsg'], '', 'error' );
		}

		return view( $this->template . '/post_news.html' );
	}

	//同步图文消息
	public function syncNews() {
		if ( isset( $_GET['pos'] ) ) {
			$pos    = Request::get( 'pos' );
			$param  = [
				//素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
				"type"   => 'news',
				//从全部素材的该偏移位置开始返回，0表示从第一个素材 返回
				"offset" => $pos,
				//返回素材的数量，取值在1到20之间
				"count"  => 10
			];
			$result = \WeChat::instance( 'material' )->lists( $param );
			if ( isset( $result['errcode'] ) ) {
				message( '同步图文消息失败' . $result['errmsg'], site_url( 'site/news' ), 'error' );
			} else {
				if ( empty( $result['item'] ) ) {
					message( '全部同步完毕', url( 'site/news' ), 'success' );
				}
				//创建上传目录
				\Dir::create( c( 'upload.path' ) . '/' . date( 'Y/m/d' ) );
				foreach ( $result['item'] as $k => $v ) {
					$field = Material::where( 'media_id', $v['media_id'] )->where( 'siteid', SITEID )->first();
					if ( empty( $field ) ) {
						//保存缩略图
						foreach ( $v['content']['news_item'] as $n => $m ) {
							$imgContent = WeChat::instance( 'material' )->getMaterial( $m['thumb_media_id'] );
							$pic        = c( 'upload.path' ) . '/' . date( 'Y/m/d' ) . '/' . time() . mt_rand( 0, 999 ) . '.jpg';
							file_put_contents( $pic, $imgContent );
							$v['content']['news_item'][ $n ]['pic'] = $pic;
						}
						$model = new Material();
						//本地不存在时添加到数据库
						$model['media_id'] = $v['media_id'];
						$model['type']     = 'news';
						$model['data']     = json_encode( $v['content']['news_item'], JSON_UNESCAPED_UNICODE );
						$model->save();
					}
				}
			}
			$end = $pos + $result['item_count'];
			message( "准备同步[{$pos} ~ {$end}]图文消息", url( 'site/syncNews', [ 'pos' => $end ] ), 'success' );
		}
		message( '准备同步图文消息', url( 'site/syncNews', [ 'pos' => 0 ] ), 'success' );
	}

	//添加图文
	public function postNews() {
		$id    = Request::get( 'id' );
		$model = $id ? Material::find( $id ) : new Material();
		if ( IS_POST ) {
			$articles = json_decode( Request::post( 'data' ), JSON_UNESCAPED_UNICODE );
			if ( $id ) {
				//编辑时修改微信图文消息
				foreach ( $articles['articles'] as $k => $v ) {
					$editData = [
						"media_id" => $model['media_id'],
						"index"    => $k,
						"articles" => $v
					];
					$res      = WeChat::instance( 'material' )->editNews( $editData );
					if ( $res['errcode'] != 0 ) {
						//推送到微信失败
						message( $res['errmsg'], '', 'error' );
					}
				}
				$media_id = $model['media_id'];
			} else {
				//新增时推送到微信
				$res = WeChat::instance( 'material' )->addNews( $articles );
				if ( ! isset( $res['media_id'] ) ) {
					//推送到微信失败
					message( $res['errmsg'], '', 'error' );
				}
				$media_id = $res['media_id'];
			}
			$model['id']       = $id;
			$model['type']     = 'news';
			$model['data']     = Request::post( 'data' );
			$model['media_id'] = $media_id;
			$model['status']   = 1;
			$model->save();
			message( '图文消息保存成功', url( 'site/news' ), 'success' );
		}
		if ( $id ) {
			$field = $model['data'];
			if ( ! $field ) {
				message( '图文消息不存在', 'back', 'error' );
			}
		} else {
			$author = v( 'site.info.name' );
			$field  = <<<str
			{
                "articles": [{
                    "title": '',
                    "thumb_media_id": '',
                    "author": '$author',
                    "digest": '',
                    "show_cover_pic": 1,
                    "content": '',
                    "content_source_url": '',
                    'pic': ''
                }]
            }
str;
		}

		return view( $this->template . '/post_news.html' )->with( 'field', $field );
	}

	//根据文件获取微信media_id
	public function getMediaId() {
		$file = q( 'post.file' );
		$res  = Material::where( 'file', $file )->first();
		if ( $res ) {
			return [ 'valid' => 1, 'media_id' => $res['media_id'] ];
		} else {
			//文件不存在时表示没有上传到微信,上传之哟.
			$data = WeChat::instance( 'material' )->upload( 'image', $file, 0 );
			if ( isset( $data['errcode'] ) ) {
				return [ 'valid' => 0, 'message' => $data['errmsg'] ];
			} else {
				$tab['type']       = 'image';
				$tab['file']       = $file;
				$tab['media_id']   = $data['media_id'];
				$tab['url']        = $data['url'];
				$tab['siteid']     = SITEID;
				$tab['createtime'] = time();
				$tab['status']     = 1;
				$this->db->insert( $tab );

				return [ 'valid' => 1, 'media_id' => $data['media_id'] ];
			}
		}
	}

	//群发图文消息
	public function users() {
		$user = Member::where( 'openid', '<>', '' )->where( 'siteid', SITEID )->get();
		View::with( 'user', $user );

		return view( $this->template . '/users.html' );
	}

	//群发图文消息
	public function sendNews() {
		$id                          = q( 'post.id' );
		$media_id                    = Material::where( 'id', $id )->pluck( 'media_id' );
		$data                        = [ ];
		$data['filter']['is_to_all'] = true;
		$data['filter']['group_id']  = 2;
		$data['mpnews']['media_id']  = $media_id;
		$data['msgtype']             = 'mpnews';
		$res                         = WeChat::instance( 'message' )->sendall( $data );
		if ( empty( $res['errcode'] ) ) {
			message( '图文消息群发成功,真正到用户手机需要些时间', '', 'success' );
		}
		message( '图文消息群发失败,可能达到了发送次数的上限', '', 'error' );
	}

	//预览图文消息
	public function preview() {
		$uid                        = q( 'post.uid' );
		$id                         = q( 'post.id' );
		$user                       = Db::table( 'member' )->find( $uid );
		$material                   = Material::find( $id );
		$data['touser']             = $user['openid'];
		$data['mpnews']['media_id'] = $material['media_id'];
		$data['msgtype']            = 'mpnews';
		$res                        = WeChat::instance( 'message' )->preview( $data );
		if ( empty( $res['errcode'] ) ) {
			message( '发送消息成功,请查看微信客户端', '', 'success' );
		} else {
			message( '发送失败' . $res['errmsg'], '', 'error' );
		}
	}
}