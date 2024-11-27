import { Entity, Column, PrimaryGeneratedColumn, ManyToOne, CreateDateColumn, UpdateDateColumn } from 'typeorm';
import { Category } from '../categories/category.entity';
import { ApiProperty } from '@nestjs/swagger';

@Entity()
export class Product {
  @ApiProperty({
    description: 'Unique identifier for the product',
    example: 'uuid-product-id',
  })
  @PrimaryGeneratedColumn('uuid')
  id: string;

  @ApiProperty({
    description: 'Name of the product',
    example: 'Smartphone',
  })
  @Column({ length: 255 })
  name: string;

  @ApiProperty({
    description: 'Description of the product',
    example: 'Latest model smartphone with advanced features',
    required: false,
  })

  @Column({ type: 'text', nullable: true })
  description: string;

  @ApiProperty({
    description: 'Price of the product',
    example: 299.99,
  })
  @Column({ type: 'float' })
  price: number;

  @ApiProperty({
    description: 'URL to the image for the product',
    example: 'http://example.com/product.png',
    required: false,
  })

  @Column({ type: 'varchar', nullable: true })
  image: string;

  @ApiProperty({
    description: 'The ID of the category this product belongs to',
    example: 'uuid-category-id',
  })

  @Column({ type: 'uuid' })
  category_id: string;

  // @ApiProperty({
  //   description: 'Category of the product',
  // })

  @ManyToOne(() => Category, (category) => category.products, { onDelete: 'CASCADE' })
  category: Category;

  @ApiProperty({
    description: 'Date and time the product was created',
  })

  @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP' })
  created_at: Date;

  @ApiProperty({
    description: 'Date and time the product was last updated',
  })

  @Column({ type: 'timestamp', default: () => 'CURRENT_TIMESTAMP', onUpdate: 'CURRENT_TIMESTAMP' })
  updated_at: Date;
}
