// src/products/products.controller.ts
import { Controller, Get, Post, Body, Param, Put, Delete, Query, HttpCode, HttpStatus } from '@nestjs/common';
import { ProductsService } from './products.service';
import { CreateProductDto } from './dto/create-product.dto';
import { UpdateProductDto } from './dto/update-product.dto';
import { ApiTags, ApiOperation, ApiResponse, ApiParam, ApiQuery } from '@nestjs/swagger';
import { Product } from './product.entity';
import { Unprotected } from 'nest-keycloak-connect';
import { ApiBearerAuth} from '@nestjs/swagger';
import { Roles, RoleMatchingMode } from 'nest-keycloak-connect';
import { UseGuards } from '@nestjs/common';

@ApiTags('Products')
@ApiBearerAuth('JWT') 
@Controller('products')
export class ProductsController {
  constructor(private readonly productsService: ProductsService) {}

  @Post()
  @Roles({ roles: ['realm:app-user'] })
  @ApiOperation({ summary: 'Create a new product' })
  @ApiResponse({ status: 201, description: 'The created product.', type: Product })
  create(@Body() createProductDto: CreateProductDto): Promise<Product> {
    return this.productsService.create(createProductDto);
  }

  @Get()
  @Unprotected()
  @ApiOperation({ summary: 'Get all products' })
  @ApiQuery({ name: 'page', required: false, type: Number, description: 'The page number to retrieve' })
  @ApiQuery({ name: 'limit', required: false, type: Number, description: 'Number of items per page' })
  @ApiResponse({
    status: 200,
    description: 'A list of products.',
    schema: {
      type: 'object',
      properties: {
        items: { type: 'array', items: { $ref: '#/components/schemas/Product' } },
        meta: { type: 'object' },
      },
    },
  })
  @ApiResponse({ status: 404, description: 'No products found' })
  findAll(@Query('page') page?: number, @Query('limit') limit?: number) {
    return this.productsService.findAll(page, limit);
  }

  @Get(':id')
  @Roles({ roles: ['realm:app-user'] })
  @ApiOperation({ summary: 'Get a specific product' })
  @ApiParam({ name: 'id', description: 'Unique identifier for the product' })
  @ApiResponse({ status: 200, description: 'A specific product.', type: Product })
  @ApiResponse({ status: 404, description: 'Product not found' })
  findOne(@Param('id') id: string) {
    return this.productsService.findOne(id);
  }

  @Put(':id')
  @Roles({ roles: ['realm:app-admin'] })
  @ApiOperation({ summary: 'Update a specific product' })
  @ApiParam({ name: 'id', description: 'Unique identifier for the product' })
  @ApiResponse({ status: 200, description: 'The updated product.', type: Product })
  update(@Param('id') id: string, @Body() updateProductDto: UpdateProductDto) {
    return this.productsService.update(id, updateProductDto);
  }

  @Delete(':id')
  @Roles({ roles: ['realm:app-admin'] })
  @ApiOperation({ summary: 'Delete a specific product' })
  @ApiParam({ name: 'id', description: 'Unique identifier for the product' })
  @ApiResponse({ status: 204, description: 'Product deleted successfully' })
  @HttpCode(HttpStatus.NO_CONTENT)
  remove(@Param('id') id: string) {
    return this.productsService.remove(id);
  }
}
