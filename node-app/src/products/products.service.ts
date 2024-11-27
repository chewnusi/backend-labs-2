// src/products/products.service.ts
import { Injectable, NotFoundException } from '@nestjs/common';
import { InjectRepository } from '@nestjs/typeorm';
import { Repository, FindManyOptions } from 'typeorm';
import { Product } from './product.entity';
import { Category } from '../categories/category.entity';
import { CreateProductDto } from './dto/create-product.dto';
import { UpdateProductDto } from './dto/update-product.dto';

@Injectable()
export class ProductsService {
  constructor(
    @InjectRepository(Product)
    private productsRepository: Repository<Product>,
    
    @InjectRepository(Category)
    private categoriesRepository: Repository<Category>,
  ) {}

  async create(createProductDto: CreateProductDto): Promise<Product> {
    const category = await this.categoriesRepository.findOne({ where: { id: createProductDto.category_id } });
    if (!category) {
      throw new NotFoundException(`Category with ID ${createProductDto.category_id} not found`);
    }
    const product = this.productsRepository.create({
      ...createProductDto,
      category,
    });
    return this.productsRepository.save(product);
  }

  async findAll(page?: number, limit?: number): Promise<{ items: Product[]; meta: any }> {
    const options: FindManyOptions<Product> = { relations: ['category'], order: { created_at: 'DESC' } };
    if (page && limit) {
      options.skip = (page - 1) * limit;
      options.take = limit;
    }
    const [items, totalItems] = await this.productsRepository.findAndCount(options);
    const meta = {
      totalItems,
      itemCount: items.length,
      itemsPerPage: limit || totalItems,
      totalPages: limit ? Math.ceil(totalItems / limit) : 1,
      currentPage: page || 1,
    };
    return { items, meta };
  }

  async findOne(id: string): Promise<Product> {
    const product = await this.productsRepository.findOne({
      where: { id },
      relations: ['category'],
    });
    if (!product) {
      throw new NotFoundException(`Product with ID ${id} not found`);
    }
    return product;
  }

  async update(id: string, updateProductDto: UpdateProductDto): Promise<Product> {
    const product = await this.findOne(id);
    if (updateProductDto.category_id) {
      const category = await this.categoriesRepository.findOne({ where: { id: updateProductDto.category_id } });
      if (!category) {
        throw new NotFoundException(`Category with ID ${updateProductDto.category_id} not found`);
      }
      product.category = category;
    }
    Object.assign(product, updateProductDto);
    return this.productsRepository.save(product);
  }

  async remove(id: string): Promise<void> {
    const product = await this.findOne(id);
    await this.productsRepository.remove(product);
  }
}
