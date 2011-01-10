class CreateCampagnePechexps < ActiveRecord::Migration
  def self.up
    create_table :campagne_pechexps do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :campagne_pechexps
  end
end
