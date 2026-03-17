<?php

namespace Drupal\udg_liston\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Random;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("udg_liston_views_field")
 */
class udg_listonViewsField extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function usesGroupBy() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Do nothing -- to override the parent query.
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['hide_alter_empty'] = ['default' => FALSE];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    // Return a random text, here you can include your custom logic.
    // Include any namespace required to call the method required to generate
    // the desired output.
    //$random = new Random();
    //return $random->name();
    //$title = strip_tags($this->view->field['title']->original_value);
   /* include '/libraries/ICS/ICS.php';
    $ics = new ICS(array(
      'location' => '123 Fake St, New York, NY',
      'description' => 'This is my description',
      'dtstart' => '2019-9-16 9:00AM',
      'dtend' => '2019-9-17 9:00AM',
      'summary' => 'This is my summary',
      'url' => 'http://example.com'
    ));*/
    //$title = $this->getEntity($values)->getTitle();
    //kint($this->getEntity($values)->getTitle());

    $sumary = $this->getEntity($values)->getTitle();
    $url = $this->getEntity($values)->toUrl()->setAbsolute()->toString();
     if(isset($this->getEntity($values)->get('field_lugar')->getValue()['0']['title'])){
        $location = $this->getEntity($values)->get('field_lugar')->getValue()['0']['title'];
     }else{
        $location = '';
     }


     if(isset($this->getEntity($values)->toArray()['body']['0']['value'])){
        $descripcion = strip_tags($this->getEntity($values)->toArray()['body']['0']['value']);
        $descripcion = str_replace('"', '',$descripcion);
        $descripcion = str_replace("'", "",$descripcion);
     }else{
      $descripcion = '';
     }


     if (isset($this->getEntity($values)->toArray()['field_fecha_agenda']['0']['value'])){
        $date_start = $this->getEntity($values)->toArray()['field_fecha_agenda']['0']['value'];
        $date_start = strtotime($date_start);

        //kint($date_start);
        //kint(strtotime($date_start));
        //kint(date('Y-n-j g:iA' , $date_start));
        $date_start = date('Y-n-j g:iA' , $date_start);
     }else{
        $date_start = '';
     }
     if (isset($this->getEntity($values)->toArray()['field_fecha_agenda']['0']['value'])){
        $date_end = $this->getEntity($values)->toArray()['field_fecha_agenda']['0']['end_value'];
        $date_end = strtotime($date_end);
        $date_end = date('Y-n-j g:iA' , $date_end);
        //kint($date_end);
     }else{
        $date_end = '';
     }
     $return = '<form method="post" action="/libraries/ICS/download-ics.php">
                  <input type="hidden" name="date_start" value="'.$date_start.'">
                  <input type="hidden" name="date_end" value="'.$date_end.'">
                  <input type="hidden" name="location" value="'.$location.'">
                  <input type="hidden" name="description" value="'.$descripcion.'">
                  <input type="hidden" name="summary" value="'.$sumary.'">
                  <input type="hidden" name="url" value="'.$url.'">
                  <input type="submit" value="Agregar al calendarior">
                </form>';
    /* $return = '<form method="post" action="/libraries/ICS/download-ics.php">
                  <input type="hidden" name="date_start" value="2017-1-16 9:00AM">
                  <input type="hidden" name="date_end" value="2017-1-16 10:00AM">
                  <input type="hidden" name="location" value="123 Fake St, New York, NY">
                  <input type="hidden" name="description" value="This is my description">
                  <input type="hidden" name="summary" value="This is my summary">
                  <input type="hidden" name="url" value="http://example.com">
                  <input type="submit" value="Agregar al calendarior">
                </form>';*/

     //kint($this->getEntity($values)->toUrl()->setAbsolute()->toString());
     //kint($this->getEntity($values)->toUrl()->toString());


    //kint($this->getEntity($values)->get('field_lugar')->getValue()['0']['uri']);
    //kint($this->getEntity($values)->get('field_lugar')->getValue()['0']['title']);
    //kint($this->getEntity($values)->get('field_descripcion_evento')->getValue()['0']['value']);
     //kint($this->getEntity($values)->toArray()['field_fecha_agenda']['0']['value']);
     //kint($this->view->field['field_fecha_agenda']->original_value);
    //'end_date'


    //$date_start = $this->view->field['field_fecha_agenda'];
    //kint ($date_start);

    //kint($this->getEntity($values)->getTitle());
    return  check_markup($return, 'full_html');

  }

}
