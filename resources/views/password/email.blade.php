<!DOCTYPE html>
<html lang="id-ID">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>{{config('app.name')}} Reset Password OTP</title>
    <meta name="description" content="{{config('app.name')}} Reset Password OTP">
</head>
<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #fff;" leftmargin="0">
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#fff"
        style="@import url(https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap); font-family: 'Roboto', sans-serif;">
        <tr>
            <td>
                <table style="background-color: #fff; max-width:670px;  margin:0 auto;" width="100%" border="0"
                    align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                style="max-width:670px;background:#fff; text-align:center;">
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <a style="text-decoration: none; color: #212529;" href="{{route('login')}}" target="_blank">
                                            <img width="480" src="https://res.cloudinary.com/ddke6cwho/image/upload/v1692584763/educashlog-logo.png" alt="Logo" title="Logo">
                                        </a>
                                        <hr style="border-top: 1px solid #212529;border-color: #212529; margin: 20px 0px">
                                        <p style="color:#212529;">
                                            Karena anda telah meminta link reset untuk mereset password akun {{config('app.name')}}, kami mengirimkan link berikut.
                                        </p>
                                        <a href="{{route('reset_password', $token)}}" style="background-color: #e74a3b; margin: 20px 0px; text-decoration:none; font-weight:700; color:#fff;text-transform:uppercase; font-size:14px; padding:10px 24px; display:inline-block; border-radius:50px;">
                                            Reset Password
                                        </a>
                                        <p style="color:#212529;">
                                            Link reset password ini hanya berlaku selama 1 jam. <b style="color: #e74a3b;">Mohon untuk tidak memberikan link ini kepada orang lain</b>.
                                        </p>
                                        <hr style="border-top: 1px solid #212529;border-color: #212529; margin: 20px 0px 25px 0px">
                                        <small style="display:block; text-align:left !important;margin-bottom: 30px;">
                                            Jika Anda mengalami kesulitan mengklik tombol "Reset Password", salin dan tempel link di bawah ini ke browser Anda: <br>
                                            <a href="{{route('reset_password', $token)}}" style="display: block; color: #e74a3b; text-wrap: wrap; line-break: anywhere;">
                                                {{route('reset_password', $token)}}
                                            </a>
                                        </small>
                                        <p style="color:#e74a3b;"><b>{{config('app.name')}}</b></p>
                                        <p style="color:#212529;">Jl. Raya Wangi Teh Melati Km. 3,8 Bandung, Indonesia</p>
                                        <p style="color:#212529; line-height:18px; margin:20px 0 0;">
                                            Copyright ©@php echo date('Y') @endphp <a style="text-decoration: none; color: #212529;" href="{{route('login')}}">{{config('app.name')}}</a>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>