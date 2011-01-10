class AideController < AdminApplicationController
  def index
    flash[:titre] = "Aide En Ligne"
    @image="balingo.jpg"
  end
end
