// src/categories/categories.controller.ts
import { Controller, Get, Post, Body, Param, Put, Delete, Query, HttpCode, HttpStatus } from '@nestjs/common';
import { CategoriesService } from './categories.service';
import { CreateCategoryDto } from './dto/create-category.dto';
import { UpdateCategoryDto } from './dto/update-category.dto';
import { ApiTags, ApiOperation, ApiResponse, ApiParam, ApiQuery } from '@nestjs/swagger';
import { Category } from './category.entity';
import { Unprotected } from 'nest-keycloak-connect';
import { ApiBearerAuth} from '@nestjs/swagger';
import { Roles, RoleMatchingMode } from 'nest-keycloak-connect';

@ApiTags('Categories')
@ApiBearerAuth('JWT') 
@Controller('categories')
export class CategoriesController {
  constructor(private readonly categoriesService: CategoriesService) {}

  @Post()
  @Roles({ roles: ['realm:app-user'] })
  @ApiOperation({ summary: 'Create a new category' })
  @ApiResponse({ status: 201, description: 'The created category.', type: Category })
  create(@Body() createCategoryDto: CreateCategoryDto): Promise<Category> {
    return this.categoriesService.create(createCategoryDto);
  }

  @Get()
  @Unprotected()
  @ApiOperation({ summary: 'Get all categories' })
  @ApiQuery({ name: 'page', required: false, type: Number, description: 'The page number to retrieve' })
  @ApiQuery({ name: 'limit', required: false, type: Number, description: 'Number of items per page' })
  @ApiResponse({
    status: 200,
    description: 'A list of categories.',
    schema: {
      type: 'object',
      properties: {
        items: { type: 'array', items: { $ref: '#/components/schemas/Category' } },
        meta: { type: 'object' },
      },
    },
  })
  @ApiResponse({ status: 404, description: 'No categories found' })
  findAll(@Query('page') page?: number, @Query('limit') limit?: number) {
    return this.categoriesService.findAll(page, limit);
  }

  @Get(':id')
  @Roles({ roles: ['realm:app-user'] })
  @ApiOperation({ summary: 'Get a specific category' })
  @ApiParam({ name: 'id', description: 'Unique identifier for the category' })
  @ApiResponse({ status: 200, description: 'A specific category.', type: Category })
  @ApiResponse({ status: 404, description: 'Category not found' })
  findOne(@Param('id') id: string) {
    return this.categoriesService.findOne(id);
  }

  @Put(':id')
  @Roles({ roles: ['realm:app-admin'] })
  @ApiOperation({ summary: 'Update a specific category' })
  @ApiParam({ name: 'id', description: 'Unique identifier for the category' })
  @ApiResponse({ status: 200, description: 'The updated category.', type: Category })
  update(@Param('id') id: string, @Body() updateCategoryDto: UpdateCategoryDto) {
    return this.categoriesService.update(id, updateCategoryDto);
  }

  @Delete(':id')
  @Roles({ roles: ['realm:app-admin'] })
  @ApiOperation({ summary: 'Delete a specific category' })
  @ApiParam({ name: 'id', description: 'Unique identifier for the category' })
  @ApiResponse({ status: 204, description: 'Category deleted successfully' })
  @HttpCode(HttpStatus.NO_CONTENT)
  remove(@Param('id') id: string) {
    return this.categoriesService.remove(id);
  }
}
