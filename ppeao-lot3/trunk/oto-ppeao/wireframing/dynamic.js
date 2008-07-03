// JavaScript Document

function spin( obj ) 
{ 
  var spinner = document.getElementById( obj );
  var spinner_content = document.getElementById( obj+"_body" ); 
  if ( spinner_content.style.visibility == 'visible' ) 
  { 
	spinner.style.visibility = 'visible'; 
    spinner_content.style.visibility = 'hidden'; 
    spinner_content.style.height = '0px'; 
    spinner_content.style.margin = '0px'; 
	  } 
  else 
  { 
    spinner.style.visibility = 'hidden'; 
    spinner_content.style.visibility = 'visible'; 
    spinner_content.style.height = 'auto'; 
    spinner_content.style.margin = '20px 0px 20px 50px'; 
  } 
} 