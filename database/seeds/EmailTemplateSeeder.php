<?php

use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_templates')->truncate();

        $email_templates = array(

/**note-post***/
                ['template_body' => '
              <tr style="background: white;">
              <td height="5" valign="top" width="800"><hr style="color:#818181; margin-top:15px"></td>
              </tr>
              <td width="800" valign="top" height="260">
                    <table cellspacing="0" cellpadding="0" border="0" width="800">
                       <tbody>
                          <tr>
                             <td width="50" valign="top" height="260"></td>
                             <td width="700" valign="top" height="260" style="margin:0 0 20px 0;">
                                <table>
                                   <tbody>

                                      <tr>
                                         <td width="700" valign="top" height="20"><span style="color:#e6701e;font-size:16px;font-weight:bold;margin-top:5px;display:block;">Dear {{firstname}},</span></td>
                                      </tr>
                                      <tr>
                                         <td width="700" valign="top" height="5">
                                            <table cellspacing="0" cellpadding="5" border="0" width="700">
                                               <tbody>
                                                  <tr>
                                                     <td valign="top" height="20" style="color:#474747;font-size:14px;"><br>Welcome to Mygharseva! <br><br><br><br>
                                                      <p>We have received your note and it will be processed in the order it was received.</p> <p>If you sent us this note during our support hours (Mon - Sat: 8 am - 7 pm ), one of our customer support </p><p>representatives will respond within a few hours. Otherwise, you will hear from us the next business day. </p>
                                                      <p>Meanwhile, please visit our website <a href="#/home" style="color:#e6701e">https://www.mygharseva.com</a>, as we may already have answers</p> <p>to many of your questions.</p><br>
                                                      <p>Thanks for choosing Mygharseva!</p><br>
                                                      <p>PS: This email is just for reference. Please DO NOT REPLY to this email. (provided this is the case)</p>
                                                    </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                               </tbody>
                         </table>
                  </td><td width="50" valign="top" height="260"></td>
                  </tr></tbody>
                  </table>
          </td></tr>', 'type' => 'note-post', 'subject' => 'A Note Was Added For Service Request #{{ServiceRequestID}}', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

/**note-post end**/

/**note-reply **/
                 ['template_body' => '<tr style="background: white;">
  <td height="5" valign="top" width="800"><hr style="color:#818181; margin-top:15px"></td>
</tr>
              <td width="800" valign="top" height="auto">
                    <table cellspacing="0" cellpadding="0" border="0" width="800">
                       <tbody>
                          <tr>
                             <td width="50" valign="top" height="auto"></td>
                             <td width="700" valign="top" height="auto" style="margin:0 0 20px 0;">
                                <table>
                                   <tbody>

                                      <tr>
                                         <td width="700" valign="top" height="20"><span style="color:#e6701e;font-size:16px;font-weight:bold;margin-top:5px;display:block;">Dear {{username}},</span></td>
                                      </tr>

                                      <tr>
                                         <td width="700" valign="top" height="5">
                                            <table cellspacing="0" cellpadding="5" border="0" width="700">
                                               <tbody>
                                                  <tr>
                                                     <td valign="top" height="20" style="color:#474747;font-size:14px;"><br>Welcome to Mygharseva! <br><br><br><br>

                                                      <p>The following note reply was posted to your Mygharseva account on {{date}}:</p> <br>

                                                      <p>{{Notes}}</p> 
                                                      <br>
                                                      <p>Please click here to respond to this note. This is an automated email response,</p> <p>and this email address will not accept incoming messages</p><br>
                                                    </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                                  </tbody></table>
                               </td><td width="50" valign="top" height="auto"></td>
                       </tr></tbody>
                    </table>
                  </td> </tr>', 'type' => 'note-reply', 'subject' => 'A Note Was Added For Service Request#{{ServiceRequestID}}', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

/**note reply end**/

/**Quote Submit **/

                 ['template_body' => '<tr style="background: white;">
  <td height="5" valign="top" width="800"><hr style="color:#818181; margin-top:15px"></td>
</tr>
              <td width="800" valign="top" height="auto">
                    <table cellspacing="0" cellpadding="0" border="0" width="800">
                       <tbody>
                          <tr>
                             <td width="50" valign="top" height="auto"></td>
                             <td width="700" valign="top" height="auto" style="margin:0 0 20px 0;">
                                <table>
                                   <tbody>

                                      <tr>
                                         <td width="700" valign="top" height="20"><span style="color:#474747;font-size:16px;font-weight:bold;;margin-top:5px;display:block;">Hello {{firstname}},</span></td>
                                      </tr>
                                      <tr>
                                         <td width="700" valign="top" height="5">
                                            <table cellspacing="0" cellpadding="5" border="0" width="700">
                                               <tbody>
                                                  <tr>
                                                     <td valign="top" height="20" style="color:#474747;font-size:14px;"><br>Welcome to Mygharseva! <br><br>
                                                      <p>Thank you for showing interest in our {{servicename}} service. We are glad you are here!! </p>
                                                      <p>One of our expert will contact you shortly and will confirm the service request details.</p>
                                                      <p>Below are the details you entered- </p>
                                                     </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                                      <tr>
                                        <td><hr style="color:#818181;"></td>
                                      </tr>
                                      <tr>
                                         <td style="color:#e6701e;font-size:16px;font-weight:bold;">{{servicename}}</td>
                                      </tr>
                                      <tr>
                                        <td><hr style="color:#818181; margin:5px 0 10px 0;"></td>
                                      </tr>



                  </tbody></table><table style="border-collapse: collapse;border: 1px solid #eeeeee; margin:0 0 40px 0;float:left;width:300px;">
                  <tbody>
                    <tr>
                      <td colspan="2" style="border:1px #eeeeee; margin:10px 0 0 0;padding:10px 0 15px 20px;font-weight:bold;font-size:14px;color:#474747;background:#eeeeee;">Service Details</td></tr>

                      <tr><td style="padding:10px 30px 10px 20px;font-size:14px;color:#474747;background:#fafafa;">Name:</td> <td style="padding:10px 0 10px 0;color:#474747;font-size:14px;background:#fafafa;">{{customerName}}</td></tr>
                      <tr><td style="background:#fafafa;padding:10px 30px 10px 20px;color:#474747;font-size:14px;">Phone:</td> <td style="padding:10px 0 10px 0;background:#fafafa;color:#474747;font-size:14px;">{{customerPhone}}</td></tr>
                      <tr><td style="background:#fafafa;padding:10px 30px 10px 20px;font-size:14px;color:#474747;">Email:</td> <td style="background:#fafafa;padding:10px 0 10px 0;font-size:14px;color:#474747;">{{customerEmail}}</td></tr>
                      <tr><td style="background:#fafafa;padding:10px 30px 10px 20px;font-size:14px;color:#474747;">Type of Service:</td> <td style="background:#fafafa;color:#474747;padding:10px 0 10px 0;font-size:14px;">{{servicename}}</td></tr>
                      <tr><td style="background:#fafafa;padding:10px 30px 10px 20px;font-size:14px;color:#474747;">Date &amp; Time:</td> <td style="background:#fafafa;color:#474747;padding:10px 0 10px 0;font-size:14px;">{{date_time}}</td>
                      </tr>
              
                    </tbody>
                        </table>
                               </td><td width="50" valign="top" height="auto"></td>
                       </tr></tbody>
                    </table>
          </td> </tr>', 'type' => 'quote-submit', 'subject' => 'Thank you for submitting the request with MyGharSeva!', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
/**Quote Submit  end **/

/**Quote reject**/
                 ['template_body' => '
              <tr style="background: white;">
  <td height="5" valign="top" width="800"><hr style="color:#818181; margin-top:15px"></td>
              </tr>
              <td width="800" valign="top" height="auto">
                    <table cellspacing="0" cellpadding="0" border="0" width="800">
                       <tbody>
                          <tr>
                             <td width="50" valign="top" height="auto"></td>
                             <td width="700" valign="top" height="auto" style="margin:0 0 20px 0;">
                                <table>
                                   <tbody>
                                      <tr>
                                         <td width="700" valign="top" height="20"><span style="color:#e6701e;font-size:16px;font-weight:bold;margin-top:5px;display:block;">Dear {{username}},</span></td>
                                      </tr>
                                      <tr>
                                         <td width="700" valign="top" height="5">
                                            <table cellspacing="0" cellpadding="5" border="0" width="700">
                                               <tbody>
                                                  <tr>
                                                     <td valign="top" height="20" style="color:#474747;font-size:14px;"><br>Welcome to Mygharseva! <br>

                                                      <p>This email is to inform that you are not in agreement with the quotation provided for 
                                                      {{servicename}} on {{quotesubmitdate}} and would like to cancel the service request.</p>
                                                      
                                                      <p></p>
                                                      <p>Rejection Note:</p>
                                                      <p>{{notes}}</p>
                                                       <br>
                                                     </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                                  <table style="width:700px;">
                                  <tbody>
                                   <tr>
                                    <div style="color:#474747;font-size:14px;">
                                    <p> If you change your mind, please login to your account or contact MyGharSeva Customer service- 9270890890</p>
                                    <br>
                                   </tr>
                                   </tbody></table>
                               </td><td width="50" valign="top" height="auto"></td>
                            </tr>
                       </tbody>
                    </table>
                 </td> </tr>', 'type' => 'quote-reject', 'subject' => 'You have declined the quotation for {{servicename}} with MyGharSeva', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

/**Quote reject end**/

/**Quote Buy**/
['template_body' => '
              <tr style="background: white;">
  <td height="5" valign="top" width="800"><hr style="color:#818181; margin-top:15px"></td>
</tr>
              <td width="800" valign="top" height="auto">
                    <table cellspacing="0" cellpadding="0" border="0" width="800">
                       <tbody>
                          <tr>
                             <td width="50" valign="top" height="auto"></td>
                             <td width="700" valign="top" height="auto" style="margin:0 0 20px 0;">
                                <table>
                                   <tbody>
                                      <tr>
                                         <td width="700" valign="top" height="20"><span style="color:#474747;font-size:16px;font-weight:bold;;margin:5px 0 20px 0;display:block;">Dear {{firstname}},</span></td>
                                      </tr>
                                      <tr>
                                         <td width="700" valign="top" height="5">
                                            <table cellspacing="0" cellpadding="5" border="0" width="700">
                                               <tbody>
                                                  <tr>
                                                     <td valign="top" height="20" style="color:#474747;font-size:14px;">
                                                     <p>Thank you for accepting the quotation for {{servicename}} service and agreeing to pay the amount.</p><p> Below are the details of your service</p>
                                                     </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                                      <tr>
                                        <td><hr style="color:#818181;margin-bottom:30px;"></td>
                                      </tr>
                  </tbody></table><table style="border-collapse: collapse;border: 1px solid #eeeeee; margin:0 0 40px 0;float:left;width:300px;">
                  <tbody>
                    <tr>
                      <td colspan="2" style="border:1px #eeeeee; margin:10px 0 0 0;padding:10px 0 15px 20px;font-weight:bold;font-size:14px;color:#474747;background:#eeeeee;">Service Details</td></tr>
                      
                      <tr><td style="padding:10px 30px 10px 20px;font-size:14px;color:#474747;background:#fafafa;">Type of Service:</td> <td style="padding:10px 0 10px 0;color:#474747;font-size:14px;background:#fafafa;">{{servicename}}</td></tr>
                      <tr><td style="background:#fafafa;padding:10px 30px 10px 20px;color:#474747;font-size:14px;">Date 
                      &amp;Time:</td> <td style="padding:10px 0 10px 0;background:#fafafa;color:#474747;font-size:14px;">{{date_time}}</td></tr>
                    </tbody>
                        </table>
                               </td><td width="50" valign="top" height="auto"></td>
                       </tr></tbody>
                    </table>
                  </td>', 'type' => 'quote-buy', 'subject' => 'Service confirmation for {{servicename}} service request #{{ServiceRequestID}}', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
/**Quote buy end**/

/**Note post admin**/
['template_body' => '
             <tr style="background: white;">
  <td height="5" valign="top" width="800"><hr style="color:#818181; margin-top:15px"></td>
</tr>
              <td width="800" valign="top" height="260">
                    <table cellspacing="0" cellpadding="0" border="0" width="800">
                       <tbody>
                          <tr>
                             <td width="50" valign="top" height="260"></td>
                             <td width="700" valign="top" height="260" style="margin:0 0 20px 0;">
                                <table>
                                   <tbody>
                                      <tr>
                                         <td width="700" valign="top" height="20"><span style="color:#e6701e;font-size:16px;font-weight:bold;margin-top:5px;display:block;">Dear {{username}},</span></td>
                                      </tr>
                                      <tr>
                                         <td width="700" valign="top" height="5">
                                            <table cellspacing="0" cellpadding="5" border="0" width="700">
                                               <tbody>
                                                  <tr>
                                                     <td valign="top" height="20" style="color:#474747;font-size:14px;"><br>Welcome to Mygharseva! <br>
                                                      <p>Thank you for enquiring with MyGharSeva.com .</p><p> In order to assist you better with your service request,</p><p> we need more details from you. Please let us know the details below-</p><br> 

                                                      <p>{{Notes}}</p><br>

                                                      <p>We appreciate your help. It will definitely help us understand your needs better </p><p>and we should be able to process your request faster.</p> 
                                                    </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                                 </tbody>
                            </table>
                          </td>
                          <td width="50" valign="top" height="260"></td>
                       </tr></tbody>
                    </table>
                  </td>
                  </tr>', 'type' => 'note-post-admin', 'subject' => 'Need More Details for your Service Request #{{ServiceRequestID}}', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
/**note post admin end**/

/**Quote publish**/
                ['template_body' => '
              <tr style="background: white;">
  <td height="5" valign="top" width="800"><hr style="color:#818181; margin-top:15px"></td>
</tr>
              <td width="800" valign="top" height="auto">
                    <table cellspacing="0" cellpadding="0" border="0" width="800">
                       <tbody>
                          <tr>
                             <td width="50" valign="top" height="auto"></td>
                             <td width="700" valign="top" height="auto" style="margin:0 0 20px 0;">
                                <table>
                                   <tbody>
                                      <tr>
                                         <td width="700" valign="top" height="20"><span style="color:#e6701e;font-size:16px;font-weight:bold;margin-top:5px;display:block;">Dear {{firstname}},</span></td>
                                      </tr>
                                      <tr>
                                         <td width="700" valign="top" height="5">
                                            <table cellspacing="0" cellpadding="5" border="0" width="700">
                                               <tbody>
                                                  <tr>
                                                     <td valign="top" height="20" style="color:#474747;font-size:14px;"><br>Welcome to Mygharseva! <br>
                                                      <p>Below is the quotation for your {{servicename}} enquiry on {{enquiryDate}}</p>
                                                      
                                                      <p>{{quotes}}</p><br>
                                                    </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                                 </tbody>
                            </table>
                          </td>
                          <td width="50" valign="top" height="auto"></td>
                       </tr></tbody>
                    </table>
                  </td>
                  </tr>', 'type' => 'quote-publish', 'subject' => 'Quotation for your {{servicename}} Enquiry.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
/**Quote publish end**/

/**Enquiry template**/
['template_body' => '<tr style="background: white;">
                 <td height="5" valign="top" width="800"><hr style="color:#818181; margin-top:15px"></td>
              </tr>
              <tr>
                 <td height="auto" valign="top" width="800">
                    <table border="0" cellpadding="0" cellspacing="0" width="800">
                       <tbody>
                          <tr>
                             <td height="auto" valign="top" width="50"></td>
                             <td height="auto" valign="top" width="700" style="margin-bottom:20px;">
                                <table>
                                   <tbody>
                                      <tr>
                                         <td height="20" valign="top" width="700"><span style="color:#;font-size:16px;font-weight:bold;margin:5px 0 0 -2px;display:block;color:e6701e;">Dear {{customername}},</span></td>
                                      </tr>
                                      <tr>
                                         <td height="5" valign="top" width="700">
                                            <table border="0" cellpadding="4" cellspacing="0" width="700">
                                               <tbody>
                                                  <tr>
                                                     <td style="margin-left:-2px;color:#474747;font-size:14px;" height="20" valign="top"><br>Welcome to MyGharSeva!<br><br>
                                                     </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                      <table style="width:700px;" height="auto">
<tbody>
  <tr>
  <div style="margin-bottom:40px;">
  <p>Thank you for contacting us. We are glad you are here!!</p>
  <p>One of our expert will contact you shortly and will confirm the serivce request.</p>
  
  <p>Below are the details you entered:</p>
  <label style="float:left;font-weight:bold;">Name:&nbsp;</label><p style="font-weight:bold;">{{customername}}</p>
  <label style="float:left;font-weight:bold;">Phone:&nbsp;</label><p style="font-weight:bold;">{{customerphone}}</p>
  <label style="float:left;font-weight:bold;">Email:&nbsp;</label><p style="font-weight:bold;">{{email}}</p>
  </div>
</tbody>
</table>




<td height="auto" valign="top" width="50"></td>
                          </tr>
                       </tbody>
                    </table>
                 </td>
              </tr>', 'type' => 'enquiry', 'subject' => 'Thank you for submitting the request with MyGharSeva!', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

/**Enquiry ends**/

/**Registration template**/

              ['template_body' => '<tr style="background: white;">
                 <td height="5" valign="top" width="800"><hr style="color:#818181; margin-top:15px"></td>
              </tr>
              <tr>
                 <td height="auto" valign="top" width="800">
                    <table border="0" cellpadding="0" cellspacing="0" width="800">
                       <tbody>
                          <tr>
                             <td height="auto" valign="top" width="50"></td>
                             <td height="auto" valign="top" width="700" style="margin-bottom:20px;">
                                <table>
                                   <tbody>
                                      <tr>
                                         <td height="20" valign="top" width="700"><span style="color:#e6701e;font-size:16px;font-weight:bold;margin:5px 0 0 -2px;display:block;">Dear {{firstname}},</span></td>
                                      </tr>
                                      <tr>
                                         <td height="5" valign="top" width="700">
                                            <table border="0" cellpadding="4" cellspacing="0" width="700">
                                               <tbody>
                                                  <tr>
                                                     <td style="margin-left:-2px;color:#474747;font-size:14px;" height="20" valign="top"><br>Welcome to MyGharSeva!<br><br><br>
                                                     </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                      <table style="width:700px;" height="auto">
<tbody>
  <tr>
  <div style="margin-bottom:40px;">
  <p>Thank you for registering with us. We would like to take this opportunity to thank you for being </p><p>a valued customer.</p>
  <p>Here are your login credentials-</p>
  
  <label style="float:left;font-weight:bold;">User Name:&nbsp;</label><span style="font-weight:bold;">{{username}}</span>
  <br><br>
  <label style="float:left;font-weight:bold;">Password:&nbsp;</label><span style="font-weight:bold;">{{password}}</span>
  <br><br>
<p>We look forward to continue providing Home Based Services to you in the long run.</p><p style="margin-bottom:4em;"> Please contact our customer service at 9270 890 890 for any questions</p>
  </div>
</tbody>
</table>




<td height="auto" valign="top" width="50"></td>
                          </tr>
                       </tbody>
                    </table>
                 </td>
              </tr>
              <tr>', 'type' => 'registration', 'subject' => 'Thank you for Registering with MyGharSeva.com', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

/**registration ends**/

['template_body' => '<tr style="background: white;">
                 <td height="5" valign="top" width="800"><hr style="color:#818181; margin-top:15px"></td>
              </tr>
              <tr>
                 <td height="auto" valign="top" width="800">
                    <table border="0" cellpadding="0" cellspacing="0" width="800">
                       <tbody>
                          <tr>
                             <td height="auto" valign="top" width="50"></td>
                             <td height="auto" valign="top" width="700" style="margin-bottom:20px;">
                                <table>
                                   <tbody>
                                      
                                      <tr>
                                         <td height="5" valign="top" width="700">
                                            <table border="0" cellpadding="4" cellspacing="0" width="700">
                                               <tbody>
                                                  <tr>
                                                     <td style="margin-left:-2px;color:#474747;font-size:14px;" height="20" valign="top"><br>Welcome to MyGharSeva!<br><br><br>
                                                     </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                        <table style="width:700px;" height="auto">
                  <tbody>
                    <tr>
                    <div style="margin-bottom:40px;">
                    <p>Thank you for subscribing to My Ghar Seva.</p>
                    <p>We′ll try not to disappoint you and send you stuff that′s worth while :)</p>
                    <br><br><br>
                    <p>{{unsubscribe}}</p>
                    </div>
                  </tbody>
                  </table>
                  <td height="auto" valign="top" width="50"></td>
                                            </tr>
                                         </tbody>
                                      </table>
                                   </td>
</tr>', 'type' => 'subscription', 'subject' => 'Thank you for subscribing!', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1]

                 );
            	 DB::table('email_templates')->insert($email_templates);
    }
}
