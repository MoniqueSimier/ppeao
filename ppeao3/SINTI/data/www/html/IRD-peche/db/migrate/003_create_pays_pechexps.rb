class CreatePaysPechexps < ActiveRecord::Migration
  def self.up
    create_table :pays_pechexps do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :pays_pechexps
  end
end
