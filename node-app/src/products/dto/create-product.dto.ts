import { IsString, IsOptional, IsUrl, IsNumber, IsUUID, Min } from 'class-validator';
import { ApiProperty } from '@nestjs/swagger';

export class CreateProductDto {
  @ApiProperty({
    description: 'Name of the product',
    example: 'Smartphone',
  })
  @IsString()
  name: string;

  @ApiProperty({
    description: 'Description of the product',
    example: 'Latest model smartphone with advanced features',
    required: false,
  })
  @IsOptional()
  @IsString()
  description?: string;

  @ApiProperty({
    description: 'Price of the product',
    example: 299.99,
  })
  @IsNumber()
  @Min(0)
  price: number;

  @ApiProperty({
    description: 'URL to the image for the product',
    example: 'http://example.com/product.png',
    required: false,
  })
  @IsOptional()
  @IsUrl()
  image?: string;

  @ApiProperty({
    description: 'The ID of the category this product belongs to',
    example: 'uuid-category-id',
  })
  @IsUUID()
  category_id: string;
}