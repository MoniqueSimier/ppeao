class CreateActiviteCibles < ActiveRecord::Migration
  def self.up
    create_table :activite_cibles do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :activite_cibles
  end
end
