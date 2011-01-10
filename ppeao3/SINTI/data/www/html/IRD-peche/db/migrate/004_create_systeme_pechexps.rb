class CreateSystemePechexps < ActiveRecord::Migration
  def self.up
    create_table :systeme_pechexps do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :systeme_pechexps
  end
end
