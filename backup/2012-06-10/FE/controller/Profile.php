<?php
class Profile extends Controller
{
	public $helpers = array('Html', 'HtmlTemplate', 'Form');
  public $formValidator=array
  (
    'Form_ProfSearch' => array
    (
      'name' => array
      (
        'NamensLaenge' => array
        (
         'rule' => array('isAlphaNumeric'), 'error' => 'Invalid string '
        )
      )
    )
  );

  public function index($param = null)
  {
    $this->view('index.tpl.php');
    
    $this->htmltemplate->setTitle('TESTTITEL ABS');
    
    $this->set('peter','search');
 
    if($this->validateForm('Form_ProfSearch'))
    {
       $this->set('peter',$this->form->getValue('name'));
       
       
    }
  }
  
  
 

}
?>