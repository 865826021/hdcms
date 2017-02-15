<?php namespace module\ucenter\system;

class Tag {
	public function header( $attr ) {
		$url = isset( $attr['url'] ) ? $attr['url'] : url( 'member.index' );
		$php = <<<str
<div class="container">
		<nav class="navbar navbar-default navbar-fixed-top uc-header">
			<div class="navbar-header">
				<a class="navbar-brand" href="{$url}" style="position: absolute;">
					<i class="fa fa-chevron-left"></i>
				</a>
				<p class="navbar-text navbar-right text-center">{$attr['title']}</p>
			</div>
		</nav>
	</div>
	<div style="height: 55px"></div>
str;

		return $php;

	}

	//会员卡底部
	public function ticket_footer( $attr ) {
		return <<<str
<link rel="stylesheet" href="{{__ROOT__}}/module/ucenter/system/css/ticket_footer.css">
<div style="height:60px;"></div>
	<nav class="navbar navbar-default navbar-fixed-bottom card_footer">
		<a href="{{url('ticket/convert')}}&type={$_GET['type']}">
			<i class="fa fa-credit-card" aria-hidden="true"></i> 兑换
		</a>
		<a href="{{url('member/index')}}">
			<i class="fa fa-user"></i> 会员中心
		</a>
	</nav>
str;

	}
}