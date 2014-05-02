<?php

class Admin_PermissaoController extends Zend_Controller_Action {

    public function init() {
    	$this->view->jQuery()->addOnLoad("$('.breadcrumbs span span').text('Você está em: Sem permissão');");
    }

    public function indexAction() {
        // action body
    }
    
	public function helpAction() {
		$this->view->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	echo 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sit amet magna nec neque egestas aliquet. Cras non enim velit. Cras porttitor metus non erat tristique sollicitudin. Sed felis quam, venenatis eu cursus in, consequat sit amet orci. Etiam ut sapien non metus viverra tincidunt. Donec fermentum ante eu nibh ornare at rhoncus orci tristique. Proin elit tortor, adipiscing et pulvinar eget, euismod id purus. Vestibulum aliquam blandit mattis. Etiam augue dolor, venenatis sed accumsan at, feugiat sit amet nunc. Praesent tincidunt tempus libero, vitae consequat neque tempor egestas. Vestibulum non interdum nulla. Ut feugiat dignissim purus, quis egestas metus porta eleifend. Donec arcu tellus, vestibulum eu varius sed, molestie a erat. Proin mollis aliquam dui, sed eleifend nibh imperdiet ut. Quisque a nisl elit. Morbi a nunc sapien, sed fringilla lorem. Maecenas orci lorem, facilisis sed pretium nec, imperdiet quis nibh. Cras sodales nisl ut augue tempor vel volutpat lectus rutrum. Pellentesque congue purus vel turpis faucibus placerat.

Sed turpis massa, facilisis sed pulvinar ut, gravida vitae erat. Fusce pulvinar mattis rutrum. Sed lacinia, nibh ac mollis eleifend, arcu velit consequat mi, quis consequat magna mauris et eros. Fusce malesuada euismod blandit. Etiam in auctor risus. Pellentesque et lectus augue, id iaculis sem. In mollis aliquam laoreet. Vestibulum lacinia, metus a sollicitudin tincidunt, ipsum dolor facilisis ligula, non rhoncus risus velit a libero. Mauris ipsum felis, congue vel malesuada id, posuere vitae quam. Quisque vestibulum dapibus ipsum, pellentesque commodo ligula mollis varius. Vivamus sapien sem, lobortis vitae pellentesque at, ultricies blandit nunc. Nulla facilisi. Fusce a dui in orci porta pellentesque sit amet vel tortor. Duis diam nulla, pellentesque in fermentum vel, ornare eget felis. Nullam id mi diam. Pellentesque laoreet iaculis urna at fermentum. ';
	}


}

