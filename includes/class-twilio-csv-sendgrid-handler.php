<?php

/**
 * Primary handler class for SendGrid outgoing email for the TwilioCSV Plugin
 */

use Eluceo\iCal\Domain\ValueObject\Attachment;
use Eluceo\iCal\Domain\ValueObject\TimeSpan;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Uri;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\Entity\Event;
use Eluceo\iCal\Domain\Entity\Attendee;
use Eluceo\iCal\Domain\Enum\ParticipationStatus;
use Eluceo\iCal\Domain\Enum\RoleType;
use Eluceo\iCal\Domain\Enum\CalendarUserType;
use \SendGrid\Mail\Mail as Mail;

class TwilioCSVEmailHandler
{

   private $sendgrid_api_key;
   private $sendgrid_api_url;

   public function __construct()
   {
      $this->sendgrid_api_key = get_option('sendgrid_api_key');
   }

   public function send()
   {

      $email = new Mail();
      $key = $this->get_api_key()['sendgrid_api_key'];
      // Replace the email address and name with your verified sender
      $email->setFrom(
         'info@thejohnson.group',
         'The Johnson Group'
      );
      $email->setSubject('Sending with Twilio SendGrid is Fun');
      // Replace the email address and name with your recipient
      $email->addTo(
         'solo.driver.bob@gmail.com',
         'Example Sender'
      );
      $layout = $this->basic_layout();
      $email->addContent(
         'text/html',
         // '<strong>and easy to do anywhere, even with PHP</strong>'
         $layout
      );
      $sendgrid = new \SendGrid($key);
      try {
         $response = $sendgrid->send($email);
         printf("Response status: %d\n\n", $response->statusCode());

         $headers = array_filter($response->headers());
         echo "Response Headers\n\n";
         foreach ($headers as $header) {
            echo '- ' . $header . "\n";
         }
      } catch (Exception $e) {
         echo 'Caught exception: ' . $e->getMessage() . "\n";
      }
   }

   public function get_api_key()
   {
      $key = get_option('sendgrid_api_key');
      if (empty($key)) {
         return 'No API Key Set';
      }
      return $key;
   }

   public static function api_key()
   {
      $key = get_option('sendgrid_api_key');
      $key = $key['sendgrid_api_key'];
      if (empty($key)) {
         return 'No API Key Set';
      }
      return $key;
   }

   public static function sender_details()
   {
      $sender = [];
      $sender['from_email'] = get_option('sendgrid_from_email')['sendgrid_from_email'];
      $sender['from_name'] = get_option('sendgrid_from_name')['sendgrid_from_name'];
      $sender['reply_to'] = get_option('sendgrid_reply_to_email')['sendgrid_reply_to_email'];
      return $sender;
   }

   public function basic_layout()
   {
      ob_start();
      include plugin_dir_path(__FILE__) . 'assets/email/index.html';
      $html = ob_get_clean();
      return $html;
   }

   public static function send_webinar_link(array $data = [])
   {
      $key = self::api_key();
      // error_log('API Key: ' . $key);
      $mail = new Mail();
      $sender = self::sender_details();

      $current_user_full_name = TwilioCSV::user_name('full');
      $twilio_sending_number = TwilioCSV::option('twilio_phone_number');
      // if number has leading 1, trim it
      if (substr($twilio_sending_number, 0, 1) == '1') {
         $twilio_sending_number = substr($twilio_sending_number, 1);
      }
      $fancy_number = preg_replace('/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $twilio_sending_number);



      $to_email = $data['email-address'];
      $to_name = $data['full-name'];
      $briefing_id = $data['select-briefing'];
      $briefing = new TwilioCSVBriefing($briefing_id);
      $briefing_link = $briefing->_weblink;
      $briefing_time = $briefing->_scheduled;
      $briefing_friendly_time = date('l, F jS, Y \a\t g:i A', strtotime($briefing_time));
      $mail->setFrom($sender['from_email'], $sender['from_name']);
      $mail->setSubject('Webinar Link');
      $mail->addTo($to_email, $to_name);
      $mail->addContent(
         'text/html',
         '<p>Hi there,</p><p>Thank you for taking the time to speak with me today! 
         <a href="' . $briefing_link . '"><span style="font-size:12pt;">Click Here</span></a> to register for the webinar scheduled at ' . $briefing_friendly_time . '</p>
         <p><a href="' . $briefing_link . '">' . $briefing_link . '</p>
         <p>Please note, when you register you must provide the following: your name, telephone number, and your city of residence.</p>
         <p>Once you have completed registration you will receive an email confirmation and instructions for joining the webinar.</p>
         <p>Regards,</p>
         <img class="alignnone wp-image-2276" src="https://thejohnson.group/wp-content/uploads/2021/02/BlackTextLogo.png" alt="" width="106" height="69" />
         <br /><span style="font-size: 10pt;">' . $current_user_full_name . '</span>
         <br /><span style="font-size: 10pt;">Email: <a href="careers@thejohnson.group">careers@thejohnson.group</a></span>
         <br /><span style="font-size: 10pt;">Phone: <a href="tel:' . $twilio_sending_number . '">' . $fancy_number . '</a></span>'
      );

      $briefing_DTI = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $briefing_time);
      $briefing_DTI_one_hour_later = $briefing_DTI->add(new DateInterval('PT1H'));
      // $briefing_DTI = DateTime::createFromFormat('Y-m-d H:i:s', $briefing_time);
      // $briefing_DTI_one_hour_later = $briefing_DTI->add(new DateInterval('PT1H'));
      $briefing_ical = self::ical($briefing_DTI, $briefing_DTI_one_hour_later, $briefing_link, $to_name, $to_email, $sender['from_name'], $sender['from_email']);
      $ical_encoded = base64_encode(file_get_contents(plugin_dir_path(__FILE__) . 'assets/clb.ics'));
      $mail->addAttachment(
         $ical_encoded,
         'text/calendar',
         'clb.ics',
         'attachment'
      );

      $sendgrid = new \SendGrid($key);
      try {
         $response = $sendgrid->send($mail);
         printf("Response status: %d\n\n", $response->statusCode());

         $headers = array_filter($response->headers());
         echo "Response Headers\n\n";
         foreach ($headers as $header) {
            echo '- ' . $header . "\n";
         }
      } catch (Exception $e) {
         echo 'Caught exception: ' . $e->getMessage() . "\n";
      }
   }

   public static function ical($start, $end, $uri, $attendee_name, $attendee_email, $organizer_name = 'The Johnson Group', $organizer_email = '')
   {

      $start_time = new DateTime($start, false);
      $end_time = new DateTime($end, false);
      $day = new TimeSpan($start_time, $end_time);
      $location = new Location('Zoom Webinar');
      $urlAttachment = new Attachment(
         new Uri($uri),
         'text/plain'
      );

      // Organizer
      $organizer = new Organizer(
         new EmailAddress($organizer_email, $organizer_name)
      );

      // Attendee
      $attendee = new Attendee(
         new EmailAddress($attendee_email, $attendee_name)
      );
      $attendee->setCalendarUserType(CalendarUserType::INDIVIDUAL());
      $attendee->setParticipationStatus(ParticipationStatus::NEEDS_ACTION());
      $attendee->setRole(RoleType::REQ_PARTICIPANT());
      $attendee->setResponseNeededFromAttendee(true);
      $attendee->addSentBy(new EmailAddress($organizer_email, $organizer_name));
      $attendee->setDisplayName($attendee_name);
      $attendee->setLanguage('en-US');




      $event = new Event();
      $event->setOccurrence($day);
      $event->setSummary('Career Life Briefing');
      $event->setDescription('Learn more in this briefing about careers with The Johnson Group.');
      $event->setOrganizer($organizer);
      $event->addAttendee($attendee);
      $event->setLocation($location);
      $event->addAttachment($urlAttachment);

      $calendar = new Eluceo\iCal\Domain\Entity\Calendar([$event]);

      // 3. Transform domain entity into an iCalendar component
      $componentFactory = new Eluceo\iCal\Presentation\Factory\CalendarFactory();
      $calendarComponent = $componentFactory->createCalendar($calendar);

      // 4. Store file
      $file = file_put_contents(plugin_dir_path(__FILE__) . 'assets/clb.ics', (string)$calendarComponent);

      return $file;
   }

   public static function acceptance_email_contents(): string
   {
      $site = get_site_url();
      $email = <<<EMAIL
      <p>Dear <strong>{{FULLNAME}}</strong>,</p>

      <p>In accepting the opportunity to work with <strong>The Johnson Group</strong> of Globe Life Liberty National Division, you agree to complete all requirements needed to become a licensed (2-15 Life, Health, and Variable Annuities) professional. By clicking "I agree" below, you are affirming that you will do everything in your power to complete the 60-hour pre-licensing course, complete your background check, register for your license, and schedule your state exam within the next 10 days.</p>

      <p>Doing so will ensure that the agency will reserve a seat for you in the next training class. Starting the training class on time puts you in the best possible position to earn your first and subsequent checks in a timely manner, and ensures that you will be considered for a promotion in the next 90 days. Failure to complete the aforementioned requirements can result in delayed compensation, void promotion eligibility, and termination of employment consideration.</p>

      <p>To aid you in completing the licensing process you will receive daily calls from the agency's leadership team and concierge services. Do expect these calls and/or return the call within an hour of receiving a message in the event that you miss a call.</p>

      <p>Lastly, by clicking "I agree" you certify that you understand the cost and commitment necessary to become a licensed professional, and will do your best to be compliant with the outlined above.</p>

      <p>Do you have any questions? If not, please click the link below, and when we receive your registration confirmation, we will reserve your seat to begin training.</p>
      
      <p style="padding: 10px; margin: 10px; border-radius: 12px; background: #15d16c; color: #ffffff; font-size: 1em; font-weight: bold; display: inline-block; cursor: pointer;"><a style="color: #ffffff;" title="I agree!" href="$site/?agreement={{CONTACT_ID}}" target="_blank" rel="noopener">I agree!</a></p>
      
      <p>Talk to you soon.</p>

      <p>Regards,</p>
      <img class="alignnone wp-image-2276" src="https://thejohnson.group/wp-content/uploads/2021/02/BlackTextLogo.png" alt="" width="106" height="69" />
      <br /><span style="font-size: 10pt;">Email: <a href="mailto:info@thejohnson.group">info@thejohnson.group</a></span>
      <br /><span style="font-size: 10pt;">Phone: <a href="tel:+13863013703">(386) 301-3703</a></span>
      <br /><span style="font-size: 10pt;"><mark><strong>Note: This email is intended only for internal use. If you have received this email in error, please discard it and notify the admin. Thank you.</strong></mark></span>
      EMAIL;

      return $email;
   }

   public static function send_acceptance_email(array $data = [])
   {
      $result = [];
      $key = self::api_key();
      $mail = new Mail();
      $sender = self::sender_details();

      $to_email = $data['candidateEmail'];
      $to_name = $data['full-name'];
      $contact_id = $data['id'];

      $email_contents = self::acceptance_email_contents();
      $email_contents = str_replace('{{FULLNAME}}', $to_name, $email_contents);
      $email_contents = str_replace('{{CONTACT_ID}}', $contact_id, $email_contents);

      $mail->setFrom($sender['from_email'], $sender['from_name']);
      $mail->addTo($to_email, $to_name);
      $mail->setSubject('The Johnson Group Agent Agreement Letter');
      $mail->addContent('text/html', $email_contents);

      $sendgrid = new SendGrid($key);
      try {
         $response = $sendgrid->send($mail);
         if ($response->statusCode() === 202) {
            $result['success'] = true;
            $result['message'] = 'Email sent successfully.';
            $result['query'] = $data;
            $result['error'] = null;
            return $result;
         } else {
            $result['success'] = false;
            $result['message'] = 'Email failed to send.';
            $result['query'] = $data;
            $result['error'] = $response->body();
            return $result;
         }
      } catch (Exception $e) {
         echo 'Caught exception: ' . $e->getMessage() . "\n";
      }
      
      return false;
   }
}
