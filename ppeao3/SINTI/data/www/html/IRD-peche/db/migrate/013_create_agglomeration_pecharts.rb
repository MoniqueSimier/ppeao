class CreateAgglomerationPecharts < ActiveRecord::Migration
  def self.up
    create_table :agglomeration_pecharts do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :agglomeration_pecharts
  end
end
