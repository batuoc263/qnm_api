<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CronController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    public function actionTest()
    {

        $begin = time();
        $day = '25/08/2020';
        $sql = "
            SELECT distinct A.khuvuc_id, A.ten_kv, A.ten_nv, A.so_dt, nvl(LD.lap_dat, 0) lap_dat,

            nvl(td.td_duoi_6, 0) td_duoi6, nvl(td.td_6_12, 0) td_612, nvl(td.td_12_24, 0) td_1224, nvl(td.td_tren_24, 0) td_tren24,
                    nvl(tl.tl_duoi_6, 0) tl_duoi6, nvl(tl.tl_6_12, 0) tl_612, nvl(tl.tl_12_24, 0) tl_1224, nvl(tl.tl_tren_24, 0) tl_tren24,
                    nvl((td.td_duoi_6 + td.td_6_12 + td.td_12_24 + td.td_tren_24), 0) tong_tamdung,
                    nvl((tl.tl_duoi_6 + tl.tl_6_12 + tl.tl_12_24 + tl.tl_tren_24), 0) tong_thanhly
            FROM
                    (select kv1.khuvuc_id, kv1.ten_kv, n.ten_nv, n.so_dt
                        from css_qnm.khuvuc@css_hpg kv
                        left join css_qnm.khuvuc@css_hpg kv1 on kv.khuvuc_cha_id=kv1.khuvuc_id
                        left join css_qnm.khuvuc_nv@css_hpg nvk on kv.khuvuc_id=nvk.khuvuc_id
                        left join admin_qnm.nhanvien@css_hpg n on n.nhanvien_id=nvk.nhanvien_id
                        where nvk.loainv_id=51)   A

                LEFT JOIN

                    (select distinct kv1.khuvuc_id, kv1.ten_kv, n.ten_nv,
                                nvl(count(distinct CASE WHEN (t.ngay_td - t.ngay_sd)/30 < 6 THEN t.ma_tb END),0) td_duoi_6,
                                nvl(count(distinct CASE WHEN (t.ngay_td - t.ngay_sd)/30 >= 6 and (t.ngay_td - t.ngay_sd)/30 < 12 THEN t.ma_tb END),0) td_6_12,
                                nvl(count(distinct CASE WHEN (t.ngay_td - t.ngay_sd)/30 >= 12 and (t.ngay_td - t.ngay_sd)/30 <= 24 THEN t.ma_tb END),0) td_12_24,
                                nvl(count(distinct CASE WHEN (t.ngay_td - t.ngay_sd)/30 > 24 THEN t.ma_tb END),0) td_tren_24
                            from css_qnm.db_thuebao@css_hpg t
                            left join css_qnm.dbtb_kv@css_hpg k on k.thuebao_id=t.thuebao_id
                            left join css_qnm.khuvuc@css_hpg kv on kv.khuvuc_id=k.khuvuc_id
                            left join css_qnm.khuvuc@css_hpg kv1 on kv.khuvuc_cha_id=kv1.khuvuc_id
                            left join css_qnm.khuvuc_nv@css_hpg nvk on kv.khuvuc_id=nvk.khuvuc_id
                            left join admin_qnm.nhanvien@css_hpg n on n.nhanvien_id=nvk.nhanvien_id
                            where nvk.loainv_id=51 and kv1.ten_kv is not null
                            and t.dichvuvt_id in (4,1,10,9,8,7,11,13)
                            and trunc(t.ngay_td) between to_date('" . $day . "','dd/mm/yyyy') and sysdate
                            group by kv1.khuvuc_id, kv1.ten_kv, n.ten_nv, n.so_dt) TD ON A.ten_kv=TD.ten_kv and A.ten_nv=TD.ten_nv

                LEFT JOIN

                            (select distinct kv1.khuvuc_id, kv1.ten_kv, n.ten_nv,
                                nvl(count(distinct CASE WHEN (t.ngay_cat - t.ngay_sd)/30 < 6 THEN t.ma_tb END),0) tl_duoi_6,
                                nvl(count(distinct CASE WHEN (t.ngay_cat - t.ngay_sd)/30 >= 6 and (t.ngay_cat - t.ngay_sd)/30 < 12 THEN t.ma_tb END),0) tl_6_12,
                                nvl(count(distinct CASE WHEN (t.ngay_cat - t.ngay_sd)/30 >= 12 and (t.ngay_cat - t.ngay_sd)/30 <= 24 THEN t.ma_tb END),0) tl_12_24,
                                nvl(count(distinct CASE WHEN (t.ngay_cat - t.ngay_sd)/30 > 24 THEN t.ma_tb END),0) tl_tren_24
                            from css_qnm.db_thuebao@css_hpg t
                            left join css_qnm.dbtb_kv@css_hpg k on k.thuebao_id=t.thuebao_id
                            left join css_qnm.khuvuc@css_hpg kv on kv.khuvuc_id=k.khuvuc_id
                            left join css_qnm.khuvuc@css_hpg kv1 on kv.khuvuc_cha_id=kv1.khuvuc_id
                            left join css_qnm.khuvuc_nv@css_hpg nvk on kv.khuvuc_id=nvk.khuvuc_id
                            left join admin_qnm.nhanvien@css_hpg n on n.nhanvien_id=nvk.nhanvien_id
                            where nvk.loainv_id=51 and kv1.ten_kv is not null
                            and t.dichvuvt_id in (4,1,10,9,8,7,11,13)
                            and trunc(t.ngay_cat) between to_date('" . $day . "','dd/mm/yyyy') and sysdate
                            group by kv1.khuvuc_id, kv1.ten_kv, n.ten_nv, n.so_dt) TL ON A.ten_kv=TL.ten_kv and A.ten_nv=TL.ten_nv

            LEFT JOIN
                            (select kv1.khuvuc_id, kv1.ten_kv, n.ten_nv, count(distinct t.ma_tb) lap_dat
                            from css_qnm.hd_thuebao@css_hpg a
                                left join css_qnm.db_thuebao@css_hpg t on t.ma_tb=a.ma_tb
                                left join css_qnm.dbtb_kv@css_hpg k on k.thuebao_id=t.thuebao_id
                                left join css_qnm.khuvuc@css_hpg kv on kv.khuvuc_id=k.khuvuc_id
                                left join css_qnm.khuvuc@css_hpg kv1 on kv.khuvuc_cha_id=kv1.khuvuc_id
                                left join css_qnm.khuvuc_nv@css_hpg nvk on kv.khuvuc_id=nvk.khuvuc_id
                                left join admin_qnm.nhanvien@css_hpg n on n.nhanvien_id=nvk.nhanvien_id
                                left join css_qnm.kieu_ld@css_hpg d on a.kieuld_id= d.kieuld_id
                                left join css_qnm.loai_hd@css_hpg e on d.loaihd_id=e.loaihd_id
                                left join css_qnm.trangthai_tb@css_hpg tt on tt.trangthaitb_id=t.trangthaitb_id
                            where e.loaihd_id in (1) and a.tthd_id=6
                                and trunc(a.ngay_ins) between to_date('" . $day . "','dd/mm/yyyy') and sysdate
                                and nvk.loainv_id=51 and kv1.ten_kv is not null
                            group by kv1.khuvuc_id, kv1.ten_kv, n.ten_nv) LD ON A.ten_kv=LD.ten_kv and A.ten_nv=LD.ten_nv
                WHERE A.ten_kv is not null
                    and nvl(td.td_duoi_6, 0) <> 0
                    or nvl(td.td_6_12, 0) <> 0
                    or nvl(td.td_12_24, 0) <> 0
                    or nvl(td.td_tren_24, 0) <> 0
                    or nvl(tl.tl_duoi_6, 0) <> 0
                    or nvl(tl.tl_6_12, 0) <> 0
                    or nvl(tl.tl_12_24, 0) <> 0
                    or nvl(tl.tl_tren_24, 0) <> 0

                ORDER BY ten_kv, ten_nv
            ";
        $command = Yii::$app->dbreport->createCommand(
            $sql
        );
        $data = $command->queryAll();

        echo "Executed in " . (int) time() - $begin . " seconds. \n";
        foreach ($data as $row) {
            foreach ($row as $column) {
                echo "$column\t";
            }
            echo "\n";
        }
    }
}
