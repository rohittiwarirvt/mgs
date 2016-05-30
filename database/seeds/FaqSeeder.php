<?php

use Illuminate\Database\Seeder;
use App\Models\Faq;
use App\Models\Service;


class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$service = new Service();

        DB::table('faq')->truncate();
        $beauty = Service::where('service_name', 'Beauty Services')->first();
        $painting= Service::where('service_name', 'Painting')->first();
        $pest_control= Service::where('service_name', 'Pest Control')->first();
        $cleaning= Service::where('service_name', 'Home Cleaning')->first();
        $app_repair = Service::where('service_name', 'Appliance Repair')->first();
        $plumbing= Service::where('service_name', 'Plumber')->first();
        $electrician = Service::where('service_name', 'Electrician')->first();
        $carpenter = Service::where('service_name', 'Carpenter')->first();
        $interiors= Service::where('service_name', 'Interior Designer')->first();
        $pooja= Service::where('service_name', 'Pooja Services')->first();
        $drivers= Service::where('service_name', 'Driver on Demand')->first();
        $movers= Service::where('service_name', 'Movers & Packers')->first();


            $faq = array(
                // 1 appliance repair
                ['service_id' => $app_repair['id'], 'question' => 'Are your technician’s experienced?', 'answer' => 'Yes. We only bring in professionals who have been recommended and certified by experts have minimum experience of 3-5 years. They get trained regularly on new skills and features and even give training to other students.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $app_repair['id'], 'question' => 'How are we different than your regular repair workers?', 'answer' => 'First of all, this technician is coming to your home at your convenient timing. You get quality service at a competitive price. No need to worry about the security issues as we only hire professional through referral from our pool of selected members.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $app_repair['id'], 'question' => 'Do we have to provide anything to technician?', 'answer' => 'No. Our technician will carry all the tools with them. Our in-house team makes sure that the technician’s tool-kit has everything they need for the service mentioned.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $app_repair['id'], 'question' => 'Do you verify background of your technician’s?', 'answer' => 'Yes. Safety of our customer is our utmost Priority. We run a background check on all our technicians before assigning them. We also ensure safety of our technician as they are our valued resources.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $app_repair['id'], 'question' => 'What time your technician can come to my house?', 'answer' => 'Our technicians are ready to take a call from 9 am to 6 pm; but for regular and trusted customers this timings can be made flexible.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $app_repair['id'], 'question' => 'Do I have to pay anything extra?', 'answer' => 'No. All the charges will be mentioned on the website. We recommend you to pay through our App or website. In case you take any additional service, you can pay later again.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $app_repair['id'], 'question' => 'What if technician cannot come on time?', 'answer' => 'We value the time of our customer and make sure our technician arrives on time. In case of an issue we will arrange for a replacement immediately or schedule for more convenient time.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $app_repair['id'], 'question' => 'Do I have to register to place request always?', 'answer' => 'Yes. We recommend only that method. If you call the technician directly it will not be MyGharSeva’s responsibility at all. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
//2 beauty
                ['service_id' => $beauty['id'], 'question' => 'Are your beautician’s experienced? ', 'answer' => 'Yes. We only bring in professionals who have been recommended and certified by experts have minimum experience of 3-5 years. They get trained regularly on new skills and features and even give training to other students. Sometimes they may bring the student’s along upon your consult but it will be the beautician performing services for you.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $beauty['id'], 'question' => 'How are we different than your regular salon? ', 'answer' => 'First of all, this beautician is coming to your home at your convenient timing. You get everything that any salon can offer at a competitive price and equally same quality. No need to worry about hygiene of the place either. It is your own home. You can sit back and relax while getting pampered. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $beauty['id'], 'question' => 'What products and materials does your beautician use?  ', 'answer' => 'Our beautician’s have expertize to use various quality products. We use all top quality products from all herbal to Loreal, Wella, VLCC. You can specify your choice while selecting the package on booking of the service. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $beauty['id'], 'question' => 'Do we have to provide anything to beautician?', 'answer' => 'No. Our beautician will carry all the supplies with them. All you have to provide is a warm water if any service required so. Our partner- VLCC helps us check the beautician’s kit to make sure they have everything they need for the service. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $beauty['id'], 'question' => 'Do you verify background of your beautician’s? ', 'answer' => 'Yes. Safety of our customer is our utmost Priority. We run a background check on all our beauticians before assigning them. We also ensure safety of our beautician as well so many a time for evening service, our beautician’s may come with an assistance. Prior intimation will be given for the same.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $beauty['id'], 'question' => 'What time your beautician can come to my house? ', 'answer' => 'Our beautician are ready to take a call from 9 am to 6 pm; but for regular and trusted customers this timings can be made flexible.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $beauty['id'], 'question' => 'Do I have to pay anything extra?', 'answer' => 'No. All the charges will be mentioned on the website. We recommend you to pay through our App or website. In case you take any additional service, you can pay later again. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
//3 carpen
                ['service_id' => $carpenter['id'], 'question' => 'Do you repair or fix premium products under manufacturing Warranty?', 'answer' => 'No. We do not provide services for devices under manufacturing warranty. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $carpenter['id'], 'question' => 'What is the usual time taken for fixing?', 'answer' => 'It varies depending upon the issue or availability of spare parts. Customer will be informed about the timeline once the inspection is done. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $carpenter['id'], 'question' => 'Do you provide material and/or service warranty?', 'answer' => 'Yes. Warranty or guarantees on parts are offered as described on the material’s bill.  Regarding service, there is a 7 days warranty, such that if a problem of the same nature reoccurs, our services will be free of cost. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $carpenter['id'], 'question' => 'Do I still need to pay fee, if you can’t fix my repair?', 'answer' => 'There are two kind of charges- Visiting charge and Inspection charge. Minimum of Rs. 150/- visit charges are applicable if repair service is not availed. (Please note that visit charges of Rs 150 will apply if the work is done by customer, visit charges will be adjusted against the final bill) ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $carpenter['id'], 'question' => 'Do you verify background of your carpenter?', 'answer' => 'Yes. Safety of our customer is our utmost priority. We run a background check on all our technicians before assigning them. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $carpenter['id'], 'question' => 'Do we have to provide anything to technician who is fixing the issue?', 'answer' => 'No.Our technician will carry all the tools with them. Only electrical points to be provided if technician wants to use tools. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $carpenter['id'], 'question' => 'Do I have to register to place request always?', 'answer' => 'Yes. We recommend only that method. If you call the technician directly, it will not be MyGharSeva’s responsibility at all. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $carpenter['id'], 'question' => 'Do you have standard work labor charges for carpentry services?  ', 'answer' => 'No. We do not provide services for devices under manufacturing warranty. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
//4 elec
                ['service_id' => $electrician['id'], 'question' => 'Do you repair or fix premium products under manufacturing Warranty?  ', 'answer' => 'No. We do not provide services for devices under manufacturing warranty. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $electrician['id'], 'question' => 'What is the usual time taken for fixing?  ', 'answer' => 'It varies depending upon the issue or availability of spare parts. Customer will be informed about the timeline once the inspection is done. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $electrician['id'], 'question' => 'Do you provide material and/or service warranty?  ', 'answer' => 'Yes. Warranty or guarantees on parts are offered as described on the material’s bill.  Regarding service, there is a 7 days warranty, such that if a problem of the same nature reoccurs, our services will be free of cost. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $electrician['id'], 'question' => 'Do I still need to pay fee, if you can’t fix my repair?  ', 'answer' => 'There are two kind of charges- Visiting charge and Inspection charge. Minimum of Rs. 150/- visit charges are applicable if repair service is not availed. (Please note that visit charges of Rs 150 will apply if the work is done by customer, visit charges will be adjusted against the final bill) ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $electrician['id'], 'question' => 'Do you verify background of your electrician?  ', 'answer' => 'Yes. Safety of our customer is our utmost priority. We run a background check on all our technicians before assigning them. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $electrician['id'], 'question' => 'Do we have to provide anything to technician who is fixing the issue?  ', 'answer' => 'No.Our technician will carry all the tools with them. Only electrical points to be provided if technician wants to use tools. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $electrician['id'], 'question' => 'Do I have to register to place request always? ', 'answer' => 'Yes. We recommend only that method. If you call the technician directly, it will not be MyGharSeva’s responsibility at all. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $electrician['id'], 'question' => 'Do you have standard work labor charges for electrician services?  ', 'answer' => 'Yes, For Standard and minor work Labor charges will be Rs. 300 for up to 1 hour. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
//5 movers
                ['service_id' => $movers['id'], 'question' => 'Do you undertake all the shifting of goods?  ', 'answer' => 'Yes. Luggage is small or large, we provide shifting services for all sort of moving luggage from one place to another. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $movers['id'], 'question' => 'How do you protect our shifting goods?  ', 'answer' => 'We use quality packing materials such as Boxes, bubble wrap, butchers paper, packing tape, and many more to protect your items while in transit! ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $movers['id'], 'question' => 'Do you provide comprehensive shifting package like loading, transit insurance etc.?  ', 'answer' => 'Yes, we consider all. We offer skilled labor, On-Transit Insurance, Transportation, Packing, Loading & Lifting, & Successful Shifting to Destination ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $movers['id'], 'question' => 'What is the usual time taken for moving?  ', 'answer' => 'It varies depending upon luggage and the destination. Customer will be informed about the timeline once the inspection is done. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $movers['id'], 'question' => 'Do you provide material and/or service warranty?  ', 'answer' => 'Yes. Warranty or guarantees on parts are offered as described on the material’s bill.  Regarding service, there is a 7 days warranty, such that if a problem of the same nature reoccurs. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $movers['id'], 'question' => 'How much days in advance should I call for the inspection?  ', 'answer' => 'You can call MyGharSeva expert’s one week back prior of shifting the luggage. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $movers['id'], 'question' => 'How much time does it take for inspection?  ', 'answer' => 'The survey/ inspection of home will not take more than 30-45 minutes. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $movers['id'], 'question' => 'Do you verify background of your experts or labour?  ', 'answer' => 'Yes. Safety of our customer is our utmost priority. We run a background check on all our technicians before assigning them. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
//6 painting
                ['service_id' => $painting['id'], 'question' => 'Are your painter experienced? Can we see the sample work done by them?  ', 'answer' => 'Yes. Our painters are well trained by reputed and trusted company’s training center. You can check our previous work, we will have to schedule an appointment with our previous customer. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $painting['id'], 'question' => 'Do we have to provide anything to painter?  ', 'answer' => 'No. you do not have to provide anything to painter except electricity to use some equipment and water. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $painting['id'], 'question' => 'Do you verify background of your painters?  ', 'answer' => 'Yes. Our Painters are verified and background checked. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $painting['id'], 'question' => 'Do I have to pay anything extra?  ', 'answer' => 'As per the quotation accepted and any extra work done, we will notify you in advance for the additional work. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $painting['id'], 'question' => 'What if painter cannot come on time?  ', 'answer' => 'We intimate the customer and arrange from our reserve pool of painters. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $painting['id'], 'question' => 'Do I have to register to place request always?  ', 'answer' => 'Yes. That is the only safe method we suggest to our customers. Otherwise MyGharseva is not responsible for quality of work or painter. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $painting['id'], 'question' => 'Can I call painter for on-site inspection and quotation?  ', 'answer' => 'Yes. You can fill the inquiry form, talk to our sales executive team & book for inspection as well as Quotation. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $painting['id'], 'question' => 'Do you use latest technology to paint the home? Any special safety precaution do you use?  ', 'answer' => 'Yes. We use latest technology for painting and we take extra precautions to ensure safety of your valuables. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $painting['id'], 'question' => 'Do you cover everything pre-painting & post painting like shifting, covering etc?', 'answer' => 'Yes, We include covering, shifting pre and post painting. We will clean your apartment and furniture as well. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $painting['id'], 'question' => 'How are we different than your regular painter workers? What brands do you use?  ', 'answer' => 'We do hassle free painting and use mechanized painting. We use all brands Berger, Dulux, Asian, Nerolac and British etc. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
//7 pest
                ['service_id' => $pest_control['id'], 'question' => 'Is it safe to use around kids at home?  ', 'answer' => 'Yes. Our products are totally safe and reliable. We use eco-friendly and result oriented Gel treatment, you can stay at home while we do the service. In case of Bed Bugs the premises should be closed for 3 hrs after the service ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pest_control['id'], 'question' => 'What type of materials do you use and how toxic are they?  ', 'answer' => 'We use WHO (world health Organization) recommended & CIB (Central Insecticide Board) certified chemicals, which are safe to be used in human inhabitations. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pest_control['id'], 'question' => 'Do you provide any service warranty?  ', 'answer' => 'We offer Six months service warranty for single service & One year warranty for AMC, in case of any reoccurrence, we will fix the issue. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pest_control['id'], 'question' => 'Do you provide any annual maintenance contract?  ', 'answer' => 'Yes, we offer yearly packages as per your requirement. Please fill the enquiry form and we will email you all the details. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pest_control['id'], 'question' => 'Do I have to register?  ', 'answer' => 'Yes. We recommend only that method. If you call technician directly it will not be MyGharSeva’s responsibility at all. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pest_control['id'], 'question' => 'Do you verify background of your technician’s?  ', 'answer' => 'Yes. Safety of our customer is our utmost priority. We run a background check on all our technicians before assigning them. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pest_control['id'], 'question' => 'Do we have to provide anything to technician?  ', 'answer' => 'No. Our technician will carry everything with them. They will move the furniture if required by themselves. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pest_control['id'], 'question' => 'I have a bug and I dont know what it is. Can you help me?  ', 'answer' => 'Sure! Our technicians can usually identify a bug by asking you a few questions about where you found it, size, color, and general shape. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
//8 plumbing
                ['service_id' => $plumbing['id'], 'question' => 'Do you repair tap, leakage or fix premium products under manufacturing Warranty?  ', 'answer' => 'No. We do not provide services for devices under manufacturing warranty. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $plumbing['id'], 'question' => 'What is the usual time taken for fixing?  ', 'answer' => 'It varies depending upon the issue or availability of spare parts. Customer will be informed about the timeline once the inspection is done. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $plumbing['id'], 'question' => 'Do you provide material and/or service warranty?  ', 'answer' => 'Yes. Warranty or guarantees on parts are offered as described on the material’s bill.  Regarding service, there is a 7 days warranty, such that if a problem of the same nature reoccurs, our services will be free of cost. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $plumbing['id'], 'question' => 'Do I still need to pay fee, if you can’t fix my repair?  ', 'answer' => 'There are two kind of charges- Visiting charge and Inspection charge. Minimum of Rs. 150/- visit charges are applicable if repair service is not availed. (Please note that visit charges of Rs 150 will apply if the work is done by customer, visit charges will be adjusted against the final bill) ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $plumbing['id'], 'question' => 'Do you verify background of your plumber?  ', 'answer' => 'Yes. Safety of our customer is our utmost priority. We run a background check on all our technicians before assigning them. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $plumbing['id'], 'question' => 'Do we have to provide anything to technician who is fixing the issue?  ', 'answer' => 'No.Our technician will carry all the tools with them. Only electrical points to be provided if technician wants to use tools. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $plumbing['id'], 'question' => 'Do I have to register to place request always?  ', 'answer' => 'Yes. We recommend only that method. If you call the plumber directly, it will not be MyGharSeva’s responsibility at all. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $plumbing['id'], 'question' => 'Do you have standard work labor charges for plumbing services?  ', 'answer' => 'Yes, For Standard and minor work labor charges will be Rs. 300. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
//9 drivers
                ['service_id' => $drivers['id'], 'question' => 'Do you verify background of your Driver?  ', 'answer' => 'Yes. Safety of our customer is our utmost priority. We run a background check on all our Drivers before assigning them. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $drivers['id'], 'question' => 'Do you charge us on hourly basis? Is there any overtime charges?  ', 'answer' => 'We have mentioned the charges based on the day schedule like Half day or Full day. Overtime will be charged Rs.100/- per hour ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $drivers['id'], 'question' => 'Can I book the driver for a long trip? Is there any extra charges for overnight?  ', 'answer' => 'Yes. You can book the driver for outside city trip. Driver stay allowance for overnight will be Rs.150/-. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $drivers['id'], 'question' => 'Do you provide car damage warranty?  ', 'answer' => 'Yes. Accidental damage offered against the car insurance or situational analysis. We always appreciate that the car has valid insurance. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $drivers['id'], 'question' => 'Do you also have car services on rent?  ', 'answer' => 'No. We do not have car services on rent. We send the experienced driver based on demand. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $drivers['id'], 'question' => 'What is the usual time taken for Driver?  ', 'answer' => 'You will receive the service provider details once the booking is done. The driver will reach on time as per your schedule. Customer will be informed about the timeline once the booking is done. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $drivers['id'], 'question' => 'What if we need a Driver immediately on the same day of booking?  ', 'answer' => 'You can call MyGharSeva expert’s or book online 24 hours (1 day) prior. If you are making a booking after 9:00 PM online, kindly do not make a booking for earlier than 11:00 AM for the next day. Drivers subject to availability. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $drivers['id'], 'question' => 'Is your driver experienced in driving premium model?  ', 'answer' => 'Yes. Safety of customer car is our utmost priority. We always verify the skills of Drivers before assigning them. We also send the driver based on the car model. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
//10 cleaning
                ['service_id' => $cleaning['id'], 'question' => 'Are your home cleaning staff experienced?  ', 'answer' => 'Yes. We only bring in professionals who have been recommended and certified by experts have minimum experience of 3-5 years. They get trained regularly on latest cleaning appliances and products to use. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $cleaning['id'], 'question' => 'Do we have to provide anything to cleaning?  ', 'answer' => 'Our technician will carry all the supplies with them. Our in-house team makes sure that the technician’s tool-kit has everything. You just need to provide water & electricity to use appliances. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $cleaning['id'], 'question' => 'Do you verify background of your home cleaners?  ', 'answer' => 'Yes. Safety of our customer is our utmost Priority. We run a background check on all our workers before assigning them. We also ensure safety of our workers as they are our valued resources. ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $cleaning['id'], 'question' => 'Do I have to pay anything extra?  ', 'answer' => 'No. All the charges will be discussed in advance. We recommend you to pay through our App or website. In case you take any additional service, you can pay later again.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $cleaning['id'], 'question' => 'Do I have to register to place request always?  ', 'answer' => 'Yes. We recommend only that method. If you call the home cleaner directly it will not be MyGharSeva’s responsibility at all.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $cleaning['id'], 'question' => 'Can I call cleaner for on-site inspection and quotation?  ', 'answer' => 'Yes, but visit charges of Rs 150 will be applicable which will be adjusted in the final amount if work is given to us.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $cleaning['id'], 'question' => 'Do you use latest technology to clean the home? Any special safety precaution do you use?', 'answer' => 'Yes, we use latest appliances to clean the home and ensure the safety of your belongings and furniture.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $cleaning['id'], 'question' => 'What if something is damaged when my home is being cleaned?  ', 'answer' => 'We take good precautions of your furniture and household goods. If anything is damaged that will be assessed as per the case.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
//11 interiors
                ['service_id' => $interiors['id'], 'question' => 'Are your interior designer experienced? Can we see the sample work done by them?  ', 'answer' => 'Yes. Our experts are well trained by reputed and trusted company’s training center. You can check our previous work, we will have to schedule an appointment with our previous customer.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $interiors['id'], 'question' => 'Do we have to provide anything to decorator?  ', 'answer' => 'No. you do not have to provide anything to decorator except electricity to use some equipment and water.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $interiors['id'], 'question' => 'Do you verify background of your interior experts?  ', 'answer' => 'Yes. Our decorators are verified and background checked.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $interiors['id'], 'question' => 'Do I have to pay anything extra?', 'answer' => 'As per the quotation accepted and any extra work done, we will notify you in advance for the additional work.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $interiors['id'], 'question' => 'What if interior architect cannot come on time?  ', 'answer' => 'We intimate the customer and arrange from our reserve pool of decorators.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $interiors['id'], 'question' => 'Do I have to register to place request always?', 'answer' => 'Yes. That is the only safe method we suggest to our customers. Otherwise MyGharseva is not responsible for quality of work or painter.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $interiors['id'], 'question' => 'Can I call interior decorator for on-site inspection and quotation?  ', 'answer' => 'Yes. You can fill the inquiry form, talk to our sales executive team & book for inspection as well as Quotation.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $interiors['id'], 'question' => 'Do you use latest technology for doing interior at home? Any special safety precaution do you use?  ', 'answer' => 'Yes. We use latest technology for interior decoration and we take extra precautions to ensure safety of your valuables.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],
//12 pooja
                ['service_id' => $pooja['id'], 'question' => 'Is all your experts are experienced in performing Pooja?', 'answer' => 'We have experienced Pandits, Maharaj or Guruji who conducts Pooja with all rituals.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pooja['id'], 'question' => 'Do you have Pooja experts for specific religion Pooja?  ', 'answer' => 'We undertake all types of Pooja Service by considering the auspicious aspects of every religion with its Samagri.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pooja['id'], 'question' => 'Do you provide material for all type of Pooja?  ', 'answer' => 'Yes. We cover all the material of all types of Pooja Service.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pooja['id'], 'question' => 'Do you also have experts for Vastu Shanti?  ', 'answer' => 'Yes. We provide top experts who carry large experience for Vastu Shanti of office or home.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pooja['id'], 'question' => 'What is the usual time taken for Pooja Service?  ', 'answer' => 'You will receive the service provider details once the booking is done. Guruji will reach on time as per your schedule. Customer will be informed about the timeline once the booking is done.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pooja['id'], 'question' => 'What if we need a Pooja immediately on the same day of booking?  ', 'answer' => 'You can call MyGharSeva expert’s or book online 24 hours (1 day) prior. If you are making a booking after 9:00 PM online, kindly do not make a booking for earlier than 11:00 AM for the next day. Experts subject to availability.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pooja['id'], 'question' => 'Do we have to provide Dakshina to Guruji apart from standard service cost?', 'answer' => 'We pay the standard amount to our Guruji’s depending upon the Pooja. We don’t recommend to pay anything extra.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

                ['service_id' => $pooja['id'], 'question' => 'Can I book the Pooja Service for outstation?   ', 'answer' => 'Yes. You can book the experts for outstation as well depending upon the availability. Allowance for overnight will be charged extra. We appreciate if you confirm with our marketing team over Call Centre prior to booking.', 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1],

            );
             DB::table('faq')->insert($faq);
    }
}
