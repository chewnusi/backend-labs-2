import { Injectable } from '@nestjs/common';
import {Cat} from "./cat.interface";
import { InjectRepository } from '@nestjs/typeorm';
import { Repository } from 'typeorm';

@Injectable()
export class CatsService {
    private readonly cats: Cat[] = [
        {
            id: 1,
            name: "Tom",
            age: 3,
            breed: "American"
        },
        {
            id: 2,
            name: "Jerry",
            age: 2,
            breed: "British"
        }
    ];

    create(cat: Cat) {
        this.cats.push(cat);
    }

    findAll(): Cat[] {
        return this.cats;
    }
}
