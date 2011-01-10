class CreateSystemePecharts < ActiveRecord::Migration
  def self.up
    create_table :systeme_pecharts do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :systeme_pecharts
  end
end
