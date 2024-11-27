// src/categories/dto/create-category.dto.ts
import { IsString, IsOptional, IsUrl, MaxLength } from 'class-validator';
import { ApiProperty } from '@nestjs/swagger';

export class CreateCategoryDto {
  @ApiProperty({
    description: 'Name of the category',
    example: 'Electronics',
  })
  @IsString()
  @MaxLength(255)
  name: string;

  @ApiProperty({
    description: 'Description of the category',
    example: 'All kinds of electronic items',
    required: false,
  })
  @IsOptional()
  @IsString()
  description?: string;

  @ApiProperty({
    description: 'URL to the image for the category',
    example: 'http://example.com/image.png',
    required: false,
  })
  @IsOptional()
  @IsUrl()
  image?: string;
}
