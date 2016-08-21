<?php namespace module\material;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use module\hdSite;
use module\material\model\Material;

/**
 * 微信素材管理
 * Class site
 * @package module\material
 * @author 向军
 */
class site extends hdSite {
	protected $db;

	public function __construct() {
		parent::__construct();
		$this->db = new Material();
	}

	//上传素材
	public function doSiteUpload_material() {
		if ( ! is_file( $_POST['file'] ) ) {
			message( '图片文件错误', '', 'error' );
		}
		$type = $_POST['type'];
		$data = Weixin::instance( 'material' )->upload( $type, $_POST['file'], 0 );
		if ( isset( $data['errcode'] ) ) {
			message( $data['errmsg'], '', 'error' );
		} else {
			$tab['type']     = $type;
			$tab['file']     = $_POST['file'];
			$tab['media_id'] = $data['media_id'];
			$tab['url']      = $data['url'];
			$tab['status']   = 1;
			$this->db->add( $tab );
			message( '保存成功', '', 'success' );
		}
	}

	//删除素材
	public function doSiteDelMaterial() {
		$id   = q( 'post.id' );
		$data = $this->db->find( $id );
		$data = Weixin::instance( 'material' )->delete( $data['media_id'] );
		if ( isset( $data['errcode'] ) ) {
			message( $data['errmsg'], '', 'error' );
		} else {
			$this->db->where( 'id', $id )->delete();
			message( '删除成功', '', 'success' );
		}
	}

	//图片
	public function doSiteImage() {
		$data = $this->db->getLists( 'image' );
		View::with( 'data', $data );
		View::make( $this->template . '/image.html' );
	}

	//语音
	public function doSiteVoice() {
		View::make( $this->template . '/voice.html' );
	}

	//视频
	public function doSiteVideo() {
		View::make( $this->template . '/video.html' );
	}

	//图文
	public function doSiteNews() {
		$data = $this->db->where( 'siteid', SITEID )->where( 'type', 'news' )->orderBy( 'id', 'DESC' )->get();
		foreach ( $data as $k => $v ) {
			$data[ $k ]['data'] = json_decode( $v['data'], TRUE );
		}
		View::with( 'data', json_encode( $data, JSON_UNESCAPED_UNICODE ) );
		View::make( $this->template . '/news.html' );
	}

	//删除图文
	public function doSiteDelNews() {
		if ( IS_POST ) {
			$data = $this->db->find( $_POST['id'] );
			if ( ! $data ) {
				message( '图文消息不存在', '', 'error' );
			}
			$result = Weixin::instance( 'material' )->delete( $data['media_id'] );
			if ( $result['errcode'] == 0 ) {
				$this->db->where( 'id', $data['id'] )->delete();
				message( '图文消息删除成功', '', 'success' );
			}
			message( "图文消息删除失败," . $result['errmsg'], '', 'error' );
		}
		View::make( $this->template . '/post_news.html' );
	}

	//同步图文消息
	public function doSiteSyncNews() {
		if ( isset( $_GET['pos'] ) ) {
			$pos    = q( 'get.pos' );
			$param  = [
				//素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
				"type"   => 'news',
				//从全部素材的该偏移位置开始返回，0表示从第一个素材 返回
				"offset" => $pos,
				//返回素材的数量，取值在1到20之间
				"count"  => 10
			];
			$result = Weixin::instance( 'material' )->lists( $param );
			if ( isset( $result['errcode'] ) ) {
				message( '同步图文消息失败' . $result['errmsg'], site_url( 'site/news' ), 'error' );
			} else {
				if ( empty( $result['item'] ) ) {
					message( '全部同步完毕', site_url( 'site/news' ), 'success' );
				}
				foreach ( $result['item'] as $k => $v ) {
					$field = $this->db->where( 'media_id', $v['media_id'] )->where( 'siteid', SITEID )->first();
					if ( ! $field ) {
						//保存缩略图
						foreach ( $v['content']['news_item'] as $n => $m ) {
							$imgContent = Weixin::instance( 'material' )->getMaterial( $m['thumb_media_id'] );
							$pic        = c( 'upload.path' ) . '/' . date( 'Y/m/d' ) . '/' . time() . mt_rand( 0, 999 ) . '.jpg';
							file_put_contents( $pic, $imgContent );
							$v['content']['news_item'][ $n ]['pic'] = $pic;
						}
						//本地不存在时添加到数据库
						$data['articles'] = $v['content']['news_item'];
						$tab['media_id']  = $v['media_id'];
						$tab['type']      = 'news';
						$tab['data']      = json_encode( $data, JSON_UNESCAPED_UNICODE );

						$this->db->add( $tab );
					}
				}
			}
			$end = $pos + $result['item_count'];
			message( "准备同步[{$pos} ~ {$end}]图文消息", site_url( 'site/syncNews', [ 'pos' => $end ] ), 'success' );
		}
		message( '准备同步图文消息', site_url( 'site/syncNews', [ 'pos' => 0 ] ), 'success' );
	}

	//添加图文
	public function doSitePostNews() {
		$id = q( 'get.id' );
		if ( IS_POST ) {
			$articles = json_decode( $_POST['data'], JSON_UNESCAPED_UNICODE );
			//推送到微信
			$res = Weixin::instance( 'material' )->addNews( $articles );
			if ( isset( $res['media_id'] ) ) {
				if ( $id ) {
					//编辑时删除微信图文
					$field = $this->db->find( $id );
					Weixin::instance( 'material' )->delete( $field['media_id'] );
					$tab['id'] = $id;
				}
				$tab['type']     = 'news';
				$tab['data']     = $_POST['data'];
				$tab['media_id'] = $res['media_id'];
				$tab['status']   = 1;
				$action          = $id ? 'save' : 'add';
				$this->db->$action( $tab );
				message( '图文消息保存成功', site_url( 'site/news' ), 'success' );
			}
			message( $res['errmsg'], '', 'error' );
		}
		if ( $id ) {
			$field = $this->db->where( 'id', $id )->pluck( 'data' );
			if ( ! $field ) {
				message( '图文消息不存在', 'back', 'error' );
			}
		} else {
			$author = v( 'site.name' );
			$field
			        = <<<str
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
		View::with( 'field', $field );
		View::make( $this->template . '/post_news.html' );
	}

	//根据文件获取微信media_id
	public function doSiteGetMediaId() {
		$file = q( 'post.file' );
		$res  = $this->db->where( 'file', $file )->first();
		if ( $res ) {
			return [ 'valid' => 1, 'media_id' => $res['media_id'] ];
		} else {
			//文件不存在时表示没有上传到微信,上传之哟.
			$data = Weixin::instance( 'material' )->upload( 'image', $file, 0 );
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
				$this->db->add( $tab );

				return [ 'valid' => 1, 'media_id' => $data['media_id'] ];
			}
		}
	}

	//群发图文消息
	public function doSiteUsers() {
		$user = Db::table( 'member' )->where( 'openid', '<>', '' )->where( 'siteid', SITEID )->get();
		View::with( 'user', $user );
		View::make( $this->template . '/users.html' );
	}

	//群发图文消息
	public function doSiteSendNews() {
		$id                          = q( 'post.id' );
		$media_id                    = $this->db->where( 'id', $id )->pluck( 'media_id' );
		$data                        = [ ];
		$data['filter']['is_to_all'] = TRUE;
		$data['filter']['group_id']  = 2;
		$data['mpnews']['media_id']  = $media_id;
		$data['msgtype']             = 'mpnews';
		$res                         = Weixin::instance( 'message' )->sendall( $data );
		if ( empty( $res['errcode'] ) ) {
			message( '图文消息群发成功,真正到用户手机需要些时间', '', 'success' );
		}
		message( '图文消息群发失败,可能达到了发送次数的上限', '', 'error' );
	}

	//预览图文消息
	public function doSitePreview() {
		$uid                        = q( 'post.uid' );
		$id                         = q( 'post.id' );
		$user                       = Db::table( 'member' )->find( $uid );
		$material                   = $this->db->find( $id );
		$data['touser']             = $user['openid'];
		$data['mpnews']['media_id'] = $material['media_id'];
		$data['msgtype']            = 'mpnews';
		$res                        = Weixin::instance( 'message' )->preview( $data );
		if ( empty( $res['errcode'] ) ) {
			message( '发送消息成功,请查看微信客户端', '', 'success' );
		} else {
			message( '发送失败' . $res['errmsg'], '', 'error' );
		}
	}
}