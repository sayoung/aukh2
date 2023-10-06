<?php
/**
 * @file
 * Contains \Drupal\dardev_dashbord\Controller\EnoteController.
 */

namespace Drupal\dardev_dashbord\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Site\Settings;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Drupal\user\Entity\User;
use Drupal\Core\Datetime\DrupalDateTime;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class getWorkdays extends ControllerBase
{
   	/**
 * Count the number of working days between two dates.
 *
 * This function calculate the number of working days between two given dates,
 * taking account of the Public festivities, Easter and Easter Morning days,
 * the day of the Patron Saint (if any) and the working Saturday.
 *
 * @param   string  $date1    Start date ('YYYY-MM-DD' format)
 * @param   string  $date2    Ending date ('YYYY-MM-DD' format)
 * @param   boolean $workSat  TRUE if Saturday is a working day
 * @param   string  $patron   Day of the Patron Saint ('MM-DD' format)
 * @return  integer           Number of working days ('zero' on error)
 *
 * @author Massimo Simonini <massiws@gmail.com>
 */
 function easter_date($year) {
  $a = $year % 19;
  $b = floor($year / 100);
  $c = $year % 100;
  $d = floor($b / 4);
  $e = $b % 4;
  $f = floor(($b + 8) / 25);
  $g = floor(($b - $f + 1) / 3);
  $h = (19 * $a + $b - $d - $g + 15) % 30;
  $i = floor($c / 4);
  $k = $c % 4;
  $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
  $m = floor(($a + 11 * $h + 22 * $l) / 451);
  $month = floor(($h + $l - 7 * $m + 114) / 31);
  $day = ($h + $l - 7 * $m + 114) % 31 + 1;
  return strtotime($year . '-' . $month . '-' . $day);
}
function getWorkdaysFun($date1, $date2, $workSat = FALSE, $patron = NULL) {
    if (!defined('SATURDAY')) define('SATURDAY', 6);
    if (!defined('SUNDAY')) define('SUNDAY', 0);

    // Array of all public festivities
    $publicHolidays = array('01-01', '01-11', '05-01', '07-30', '08-20', '08-21', '11-06', '11-18', '04-22', '04-21', '06-27', '06-28', '07-20', '09-28');
    // The Patron day (if any) is added to public festivities
    if ($patron) {
        $publicHolidays[] = $patron;
    }

    /*
     * Array of all Easter Mondays in the given interval
     */
    $yearStart = date('Y', strtotime($date1));
    $yearEnd   = date('Y', strtotime($date2));

    for ($i = $yearStart; $i <= $yearEnd; $i++) {
        $easter = date('Y-m-d', $this->easter_date($i));
        list($y, $m, $g) = explode("-", $easter);
        $monday = mktime(0,0,0, date($m), date($g)+1, date($y));
        $easterMondays[] = $monday;
    }

    $start = strtotime($date1);
    $end   = strtotime($date2);
    $workhours = 0;
    for ($i = $start; $i <= $end; $i = strtotime("+1 hour", $i)) {
        $day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
        $hour = date("H", $i); // 0-23
        $mmgg = date('m-d', $i);
        if ($day != SUNDAY &&
            !in_array($mmgg, $publicHolidays) &&
            !in_array($i, $easterMondays) &&
            !($day == SATURDAY && $workSat == FALSE) &&
            ($hour >= 0 && $hour <= 24)) {
            // Work hour is defined as between 8AM and 5PM
            $workhours++;
        }
    }

    return intval($workhours);
}



    }

   