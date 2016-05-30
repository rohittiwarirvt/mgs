<!DOCTYPE html>
<html lang="en-EN">
    <head>
        <meta charset="utf-8">
         <title>MyGharSeva</title>
    </head>
		<body style="border:0;margin:0px;background:#fafafa;font-family:Arial;color: #474747;font-size: 14px;">
		<table style="background:#FFF; border: 1px solid #e6e6e6;" align="center" background="FFF" border="0" cellpadding="0" cellspacing="0" width="800">
      <tbody>
         <tr style="background: white;">
            <td height="10" valign="top" width="800"></td>
              </tr>
              <tr style="background:white;">
                 <td height="19" valign="top" width="800" >
                    <table border="0" cellpadding="0" cellspacing="0" width="800">
                       <tbody>
                          <tr>
                             <td height="39" valign="top" width="50"></td>
                             <td height="39" valign="top" width="254"><a href="#" target="_blank" name="LogoImage"><img style="border:none;display:block;margin:10px 0 0 0;height:50px;" title="MyGharSeva" alt="MyGharSeva" src="/var/www/mgs/public/themes/mgs/images/mgs-logo.png" name="logo.png"></a></td>
                             <td align="right" height="29" valign="top" width="446" style="padding:15px 0 15px 0px;"><span style="color:#000000;font-size:10px;">Customer Service Phone Number |</span><span style="color:e6701e;font-size:12px;">&nbsp;9260&nbsp;790&nbsp;790&nbsp; </span><br>
                                <span style="color:#000000;font-size:10px;">One Call, We Do it All</span>
                             </td>
                             <td height="39" valign="top" width="50"></td>
                          </tr>
                       </tbody>
                    </table>
                 </td>
              </tr>


<tr style="background: white;">
                 <td height="5" valign="top" width="800"><hr style="color:#818181; margin-top:15px"></td>
              </tr>
              <tr>
                 <td height="400" valign="top" width="800">
                    <table border="0" cellpadding="0" cellspacing="0" width="800">
                       <tbody>
                          <tr>
                             <td height="400" valign="top" width="50"></td>
                             <td height="400" valign="top" width="700" style="margin-bottom:20px;">
                                <table>
                                   <tbody>
                                   

                                      <tr>
                                         <td height="20" valign="top" width="700"><span style="color:#474747;font-size:16px;font-weight:bold;margin:5px 0 0 -2px;display:block;">Dear {{ $user->username }}</span></td>
                                      </tr>
                                      <tr>
                                         <td height="5" valign="top" width="700">
                                            <table border="0" cellpadding="4" cellspacing="0" width="700">
                                               <tbody>
                                                  <tr>
                                                     <td style="margin-left:-2px;color:#474747;font-size:14px;line-height:5px;" height="20" valign="top";><br>Welcome to MyGharSeva!<br><br>
                                                     </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                                                                    
<table style="width:700px;">
<tbody>
  <tr>
  <div>
  <p>You have requested to reset your password for  MyGharSeva account.<br>
Please click this link to change your password.<br><a href="{{ $link = url('/#/passwordreset/'. $user->email . '/' . $token) }}"> {{$link}}</a><br>Or<br>
Copy the link the browser in case you are unable to open it.</p>
  <p>Please contact our customer service at 9270 890 890 for any questions.</p>
</tbody>
</table>
<td height="400" valign="top" width="50"></td>
                          </tr>
                       </tbody>
                    </table>
                 </td>
              </tr>
              <tr>

     <tr>
<tr>
            <td style="color:#474747;font-size:14px;margin:0 0 20px 20px;padding-left:45px;">Please Contact MyGharSeva Customer Service-9270890890 for any queries related to your quotation</td>
            </tr>
<tr><td style="padding:0 20px 20px 20px;"><hr></td></tr>
            <tr>
            <td style="color:#474747;font-size:14px;padding-left:45px;">Thank You<br><strong><span style="margin-bottom:30px;">Team MyGharSeva</span><strong></td>
            </tr>

              <tr><td style="padding:15px 0px 40px 0px;"><hr style="color:#818181"></td></tr>

              <tr>
              
                 <td height="19" valign="top" width="800">
                    <table style="width: 100%;">
                       <tbody>
                          <tr>
                             <td style="margin-bottom:15px;font-size:14px;color:#6b6b6b"><center>
                                  Book Now We Will Call You ASAP!
                        
                             </center></td>
                             <tr/>
                            <tr><td><br></td></tr>
                             <tr>
                           
                             <td><center><a style="border: 1px solid #e6701e; background: #e6701e;font-size:17px;color:#ffffff; padding:15px;" href= "{{ URL::to('/#/home') }}">Book A Service</a></center></td>
                             <tr/>
                             <tr><td><br></td></tr>
                             <tr>
                             <td style="font-weight:bold"><center><span style="olor:#6b6b6b;font-size:14px;">Or Call </span><span style="color:#e6701e;font-size:19px;">&nbsp;9260&nbsp;790&nbsp;790</span></center></td>
                          </tr>
                       </tbody>
                    </table>
                 </td>

              </tr>

              <tr>
                 <td height="23" valign="top" width="800"></td>
              </tr>
           </tbody>
        </table>  
        <!--footer ends-->          
  </body>
</html>