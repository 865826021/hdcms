<?php namespace module\news\system;
use module\HdProcessor;

/**
 * 图文消息处理
 * Class processor
 * @package module\news
 */
class Processor extends HdProcessor {
	public function handle( $rid ) {
		$parentNews = Db::table( 'reply_news' )->where( 'rid', $rid )->where( 'pid', 0 )->orderBy( 'rand()' )->first();
		if ( $parentNews ) {
			$news = Db::table( 'reply_news' )->where( 'pid', $parentNews['id'] )->get() ?: [ ];
			array_unshift( $news, $parentNews );
			foreach ( $news as $f ) {
				$d['title']       = $f['title'];
				$d['discription'] = $f['description'];
				$d['picurl']      = __ROOT__ . '/' . $f['thumb'];
				if ( empty( $f['url'] ) ) {
					$d['url'] = __ROOT__ . "?m=news&action=controller/site/show&id={$f['id']}&siteid=" . SITEID;
				} else {
					$d['url'] = preg_match( '/^http/i', $f['url'] ) ? $f['url'] : __ROOT__ . '/' . $f['url'];
				}
				$data[] = $d;
			}
			$this->news( $data );
		}
	}
}