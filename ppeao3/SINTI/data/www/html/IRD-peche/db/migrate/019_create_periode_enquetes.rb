class CreatePeriodeEnquetes < ActiveRecord::Migration
  def self.up
    create_table :periode_enquetes do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :periode_enquetes
  end
end
