//**************************************************
// PortageAutpCM.js
//**************************************************
// Creation le 21-10-2008 par Yann Laurent
// Gestion de l'affichage des CR dans le portage auto
// CM = cache misere car le code ci-dessous est miserable.... J'ai honte...
//**************************************************

window.addEvent('domready', function() {
	var status = {
		'true': 'Ouvert',
		'false': 'Ferm&eacute;'
	};
	//-vertical
	// that's for documentation

	var myVerticalSlide = new Fx.Slide('vertical_slide');
	myVerticalSlide.hide();
	$('vertical_status').set('html', status[myVerticalSlide.open]);
	$('v_slidein').addEvent('click', function(e){
		e.stop();
		myVerticalSlide.slideIn();
	});
	$('v_slideout').addEvent('click', function(e){
		e.stop();
		myVerticalSlide.slideOut();
	});
	
	// When Vertical Slide ends its transition, we check for its status
	// note that complete will not affect 'hide' and 'show' methods
	myVerticalSlide.addEvent('complete', function() {
		$('vertical_status').set('html', status[myVerticalSlide.open]);
	});
	
	// Warning : uggly piece of code to be replaced by a for when I have found a solution
	// to get the equivalent of ${$mavariable} in javascript...
	// CR1
	var myVerticalSlide1 = new Fx.Slide('vertical_slide1');
	myVerticalSlide1.hide();
	$('vertical_status1').set('html', status[myVerticalSlide1.open]);
	$('v_slidein1').addEvent('click', function(e){
		e.stop();
		myVerticalSlide1.slideIn();
	});
	$('v_slideout1').addEvent('click', function(e){
		e.stop();
		myVerticalSlide1.slideOut();
	});
	myVerticalSlide1.addEvent('complete', function() {
		$('vertical_status1').set('html', status[myVerticalSlide1.open]);
	});
	// CR2
	var myVerticalSlide2 = new Fx.Slide('vertical_slide2');
	myVerticalSlide2.hide();
	$('vertical_status2').set('html', status[myVerticalSlide2.open]);
	$('v_slidein2').addEvent('click', function(e){
		e.stop();
		myVerticalSlide2.slideIn();
	});
	$('v_slideout2').addEvent('click', function(e){
		e.stop();
		myVerticalSlide2.slideOut();
	});
	myVerticalSlide2.addEvent('complete', function() {
		$('vertical_status2').set('html', status[myVerticalSlide2.open]);
	});
	// CR3
	var myVerticalSlide3 = new Fx.Slide('vertical_slide3');
	myVerticalSlide3.hide();
	$('vertical_status3').set('html', status[myVerticalSlide3.open]);
	$('v_slidein3').addEvent('click', function(e){
		e.stop();
		myVerticalSlide3.slideIn();
	});
	$('v_slideout3').addEvent('click', function(e){
		e.stop();
		myVerticalSlide3.slideOut();
	});
	myVerticalSlide3.addEvent('complete', function() {
		$('vertical_status3').set('html', status[myVerticalSlide3.open]);
	});
	// CR4
	var myVerticalSlide4 = new Fx.Slide('vertical_slide4');
	myVerticalSlide4.hide();
	$('vertical_status4').set('html', status[myVerticalSlide4.open]);
	$('v_slidein4').addEvent('click', function(e){
		e.stop();
		myVerticalSlide4.slideIn();
	});
	$('v_slideout4').addEvent('click', function(e){
		e.stop();
		myVerticalSlide4.slideOut();
	});
	myVerticalSlide4.addEvent('complete', function() {
		$('vertical_status4').set('html', status[myVerticalSlide4.open]);
	});
	// CR5
	var myVerticalSlide5 = new Fx.Slide('vertical_slide5');
	myVerticalSlide5.hide();
	$('vertical_status5').set('html', status[myVerticalSlide5.open]);
	$('v_slidein5').addEvent('click', function(e){
		e.stop();
		myVerticalSlide5.slideIn();
	});
	$('v_slideout5').addEvent('click', function(e){
		e.stop();
		myVerticalSlide5.slideOut();
	});
	myVerticalSlide5.addEvent('complete', function() {
		$('vertical_status5').set('html', status[myVerticalSlide5.open]);
	});
	// CR6
	var myVerticalSlide6 = new Fx.Slide('vertical_slide6');
	myVerticalSlide6.hide();
	$('vertical_status6').set('html', status[myVerticalSlide6.open]);
	$('v_slidein6').addEvent('click', function(e){
		e.stop();
		myVerticalSlide6.slideIn();
	});
	$('v_slideout6').addEvent('click', function(e){
		e.stop();
		myVerticalSlide6.slideOut();
	});
	myVerticalSlide6.addEvent('complete', function() {
		$('vertical_status6').set('html', status[myVerticalSlide6.open]);
	});
	// CR7
	var myVerticalSlide7 = new Fx.Slide('vertical_slide7');
	myVerticalSlide7.hide();
	$('vertical_status7').set('html', status[myVerticalSlide7.open]);
	$('v_slidein7').addEvent('click', function(e){
		e.stop();
		myVerticalSlide7.slideIn();
	});
	$('v_slideout7').addEvent('click', function(e){
		e.stop();
		myVerticalSlide7.slideOut();
	});
	myVerticalSlide7.addEvent('complete', function() {
		$('vertical_status7').set('html', status[myVerticalSlid7.open]);
	});
});