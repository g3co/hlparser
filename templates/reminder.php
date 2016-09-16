<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width"/>
</head>
<body style="margin: 0;padding: 0;font-size: 100%; text-decoration: none; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65;">
<table class="body-wrap" style="width: 100% !important;height: 100%;color: #454545;background: #efefef;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;border-collapse: collapse;">
    <tr>
        <td class="container" style="display: block !important;clear: both !important;margin: 0 auto !important;max-width: 580px !important;">

            <!-- Message start -->
            <table>
                <tr>
                    <td align="center" class="masthead" style="padding: 20px 0;background: #346dbc;color: white;">

                        <h1 style="font-size:25px; margin: 0 auto !important;max-width: 90%;color: white;text-transform: uppercase;"><?=empty($h1) ? '' : $h1 ?></h1>

                    </td>
                </tr>
                <tr>
                    <td class="content" style=" background: white; padding: 30px 35px;">

                        <h2><?=empty($h2) ? '' : $h2 ?></h2>

                        <p>
                            <?php foreach($products as $product):?>
                                <a href="<?=$product['link']?>"><?=$product['name']?></a><br/>
                            <?php endforeach;?>
                        </p>

                        <table style="width:100%">
                            <tr>
                                <td align="center">
                                    <p>
                                        <a href="<?=empty($sitelink) ? '#' : $sitelink ?>" class="button" style="text-decoration: none; display: inline-block;color: white;background: #346DBC;border: solid #346DBC;border-width: 10px 20px 8px;font-weight: bold;border-radius: 4px; ">
                                            Перейти на сайт</a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
    <tr>
        <td class="container" style="display: block !important;clear: both !important;margin: 0 auto !important;max-width: 580px !important;">

            <!-- Message start -->
            <table style="width: 100%">
                <tr>
                    <td class="content footer" style="background: none; text-align: center">
                        <p>Напишите нам, если возникли вопросы <a href="mailto:<?=empty($fromemail) ? '' : $fromemail ?>"><?=empty($fromemail) ? '' : $fromemail ?></a>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
</body>
</html>