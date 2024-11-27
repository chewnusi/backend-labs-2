// src/categories/categories.service.ts
import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, FindManyOptions } from 'typeorm';
import { Category } from './category.entity';
import { CreateCategoryDto } from './dto/create-category.dto';
import { UpdateCategoryDto } from './dto/update-category.dto';

@Injectable()
export class CategoriesService {
  constructor(
    @InjectRepository(Category)
    private categoriesRepository: Repository<Category>,
  ) {}

  async create(createCategoryDto: CreateCategoryDto): Promise<Category> {
    const category = this.categoriesRepository.create(createCategoryDto);
    return this.categoriesRepository.save(category);
  }

  async findAll(page?: number, limit?: number): Promise<{ items: Category[]; meta: any }> {
    const options: FindManyOptions<Category> = {
      relations: ['products'],
      order: { created_at: 'DESC' },
    };
    if (page && limit) {
      options.skip = (page - 1) * limit;
      options.take = limit;
    }
    const [items, totalItems] = await this.categoriesRepository.findAndCount(options);
    const meta = {
      totalItems,
      itemCount: items.length,
      itemsPerPage: limit || totalItems,
      totalPages: limit ? Math.ceil(totalItems / limit) : 1,
      currentPage: page || 1,
    };
    return { items, meta };
  }

  async findOne(id: string): Promise<Category> {
    const category = await this.categoriesRepository.findOne({
      where: { id },
      relations: ['products'],
    });
    if (!category) {
      throw new NotFoundException(`Category with ID ${id} not found`);
    }
    return category;
  }

  async update(id: string, updateCategoryDto: UpdateCategoryDto): Promise<Category> {
    const category = await this.findOne(id);
    Object.assign(category, updateCategoryDto);
    return this.categoriesRepository.save(category);
  }

  async remove(id: string): Promise<void> {
    const category = await this.findOne(id);
    await this.categoriesRepository.remove(category);
  }
}
