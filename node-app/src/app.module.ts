import {Module} from '@nestjs/common';
import {AppController} from './app.controller';
import {AppService} from './app.service';
import {CatsModule} from './cats/cats.module';
import {TagsModule} from './tags/tags.module';
import {TypeOrmModule} from '@nestjs/typeorm';
import { CategoriesModule } from './categories/categories.module';
import { ProductsModule } from './products/products.module';
import { ConfigModule, ConfigService } from '@nestjs/config';
import { Category } from './categories/category.entity';
import { Product } from './products/product.entity';
import { KeycloakConnectModule, ResourceGuard, RoleGuard, AuthGuard } from 'nest-keycloak-connect';
import { APP_GUARD } from '@nestjs/core';


@Module({
    imports: [
        // CatsModule,
        CategoriesModule,
        ProductsModule,
        // TagsModule,
        ConfigModule.forRoot({
            isGlobal: true,
        }),
        TypeOrmModule.forRoot({
            type: 'postgres',
            host: 'localhost',
            port: 5432,
            username: 'pguser',
            password: 'password',
            database: 'nestjs',
            entities: [__dirname + '/**/*.entity{.ts,.js}'],
            synchronize: true,
          }),
        // TypeOrmModule.forRootAsync({
        //     imports: [ConfigModule],
            
            // useFactory: async (configService: ConfigService) => ({
            //     type: 'postgres',
            //     host: 'pg',
            //     port: '5432',
            //     username: 'pguser',
            //     password: 'password',
            //     database: 'nestjs',
            //     entities: [Category, Product],
            //     synchronize: true, 
            //     autoLoadEntities: true
            // }),            
        //     inject: [ConfigService],
        // }),
        KeycloakConnectModule.register({
            authServerUrl: 'http://localhost:5000',
            realm: 'katana',
            clientId: 'node-app',
            secret: 'qei96eLw2aeRkLymvtg212EILyvrJJUa',
            // public-client: false,
            // confidential-port: 0
        }),
    ],
    controllers: [AppController],
    providers: [
        AppService,
        { provide: APP_GUARD, useClass: AuthGuard },     
        { provide: APP_GUARD, useClass: ResourceGuard }, 
        { provide: APP_GUARD, useClass: RoleGuard },     
      ],
})
export class AppModule {
}
