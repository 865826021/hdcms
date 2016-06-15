<?php namespace module\balance;

/**
 * 支付回调通知页面
 * @author 向军
 * @url http://hdcms.com
 */
class pay
{
    /**
     * 定单处理
     * @param $tid 定单编号
     */
    public function handle($tid)
    {
        Db::table('balance')->where('tid', $tid)->update(array( 'status' => 1 ));

        //添加会员余额
        $balance = Db::table('balance')->where('siteid', v('site.siteid'))->where('tid', $tid)->first();
        $sql     = "UPDATE " . tablename('member') . " SET credit2=credit2+{$balance['fee']} WHERE uid={$balance['uid']}";
        Db::execute($sql);
    }
}