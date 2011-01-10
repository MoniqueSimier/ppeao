#File name : bienvenue_controller.rb
#Date Created : 18/11/2005
#Version : 1.0
#Author : Papa Alassane Ba <alassane.ba@sinti-group.com>
#copyright : 2006  Sinti Group
class BienvenueController < ApplicationController
  
  # author	Alassane
  # desc 
  def welcome
    
  end
  
  # author	Alassane
  # desc
  def index
    @image="balingo.jpg"
    flash[:titre] = "Accueil"
  end
  
    
end
