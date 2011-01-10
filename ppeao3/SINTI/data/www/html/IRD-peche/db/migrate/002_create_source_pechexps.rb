class CreateSourcePechexps < ActiveRecord::Migration
  def self.up
    create_table :source_pechexps do |t|
      # t.column :name, :string
    end
  end

  def self.down
    drop_table :source_pechexps
  end
end
