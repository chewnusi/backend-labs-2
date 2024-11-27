import { Entity, Column, PrimaryGeneratedColumn, OneToMany, CreateDateColumn, UpdateDateColumn } from 'typeorm';
import { Product } from '../products/product.entity';
import { ApiProperty } from '@nestjs/swagger';

@Entity()
export class Category {
  @ApiProperty({
    description: 'Unique identifier for the category',
    example: 'uuid-category-id',
  })

  @PrimaryGeneratedColumn('uuid')
  id: string;

  @ApiProperty({
    description: 'Name of the category',
    example: 'Electronics',
  })
  @Column({ length: 255 })
  name: string;

  @ApiProperty({
    description: 'Description of the category',
    example: 'All kinds of electronic items',
    required: false,
  })
  @Column({ type: 'text', nullable: true })
  description: string;

  @ApiProperty({
    description: 'URL to the image for the category',
    example: 'http://example.com/image.png',
    required: false,
  })
  @Column({ type: 'varchar', nullable: true })
  image: string;

  @ApiProperty({
    description: 'List of products in the category',
    type: () => [Product],
  })
  
  @OneToMany(() => Product, (product) => product.category, { cascade: true })
  products: Product[];

  @ApiProperty({
    description: 'Date and time the category was created',
  })
  @CreateDateColumn()
  created_at: Date;

  @ApiProperty({
    description: 'Date and time the category was last updated',
  })
  @UpdateDateColumn()
  updated_at: Date;
}