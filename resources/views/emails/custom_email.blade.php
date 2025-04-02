<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
   <title>{{ $title ?? "" }}</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body style="width: 100%;background-color: #dad7d7;padding: 0;margin: 0;font-family: Arial, sans-serif;padding-top: 20px;padding-bottom: 20px;color: #000">
   <table style="width: 600px;margin: 0 auto;border-collapse: collapse;">
      <tr>
         <td>
            <table style="width: 100%;background: #fff;margin: 0 auto;border-collapse: collapse;">
               <tr style="background-image: url({{ asset("/images/icon/icon-mail-top.png") }});background-size: 100%; background-repeat: no-repeat;">
                  <td style="padding: 20px 20px 24%;" align="right">
                     <img style="display: block;" width="90" height="90" src="{{ asset('/images/home/logoweb.png') }}" alt="Fashion Houses" />
                  </td>
               </tr>
               <tr>
                  <td style="padding: 0 30px;">
                     <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                           <td style="text-align: left;font-size: 18px;padding-bottom: 20px;color: #000000">Xin chào {{ $name ?? ""}} !!!</td>
                        </tr>
                        <tr>
                           <td style="line-height: 24px;font-size: 17px;text-align: justify;color: #000000;padding-bottom: 15px;">
                              Cảm ơn bạn đã đăng ký tài khoản trên Website <a style="color: #1c2d5b">{{ $nameweb ?? "" }}</a>. Để có được trải nghiệm và được hỗ trợ dịch vụ tốt nhất, bạn cần hoàn thiện xác thực tài khoản.</td>
                        </tr>
                        <tr>
                           <td style="font-size: 17px;color: #000000;padding-bottom: 20px">Vui lòng nhập mã xác thực dưới đây để hoàn tất quá trình này.</td>
                        </tr>
                        <tr>
                           <td style="width: 100%;text-align: center;padding-bottom: 15px;display: inline-block;">
                              <a style="text-decoration: none; background-color: #1c2d5b;padding: 10px 40px;color: #ffffff;font-size: 20px;display: inline-block;border-radius: 10px">{{ $CodeOTP ?? "" }}</a>
                           </td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td style="background-image: url({{ asset("/images/icon/icon-mail-bottom.png") }});background-size: 100% 100%;padding: 140px 0 10px 25px;background-repeat: no-repeat;padding-bottom: 5px;">
                     <table cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                           <td style="margin-top: 0;margin-bottom: 5px;text-align: left;font-size: 15px;color: #ffffff;padding-bottom: 5px;">
                              Liên hệ với bộ phận hỗ trợ
                           </td>
                        </tr>
                        <tr>
                           <td style="margin-top: 0;margin-bottom: 5px;text-align: left;font-size: 15px;color: #ffffff;padding-bottom: 5px;">
                              Hotline: 012345678
                           </td>
                        </tr>
                        <tr>
                           <td style="margin-top: 0;margin-bottom: 5px;text-align: left;font-size: 15px;color: #ffffff;padding-bottom: 5px;">
                              Trân trọng!
                           </td>
                        </tr>
                     </table>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
      <tr>
         <td>
            <table width="100%" style="border-collapse: collapse;">
               <tr>
                  <td style="margin-bottom: 0px;text-align: center;font-size: 17px;padding-top: 30px"><i><b>Chú ý:</b></i> Đây là mail tự động không nhận mail phản hồi</td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
</body>
</html>