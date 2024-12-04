import { Controller, Get } from '@nestjs/common';
import { AppService } from './app.service';
import {ApiExcludeController, ApiTags} from "@nestjs/swagger";
import { Roles, Unprotected } from 'nest-keycloak-connect';
import { ApiBearerAuth } from '@nestjs/swagger';
@ApiTags('App')
@ApiBearerAuth('JWT') 
@Controller()

// @Controller()
@ApiExcludeController()
export class AppController {
  constructor(private readonly appService: AppService) {}

  @Get()
  getHello(): string {
    return this.appService.getHello();
  }

  @Get('/public')
  @Unprotected() 
  getPublic(): string {
    return this.appService.getHello() + ' - Public';
  }
 
  @Get('/user')
  @Roles({ roles: ['realm:app-user'] })
  getUser(): string {
    return this.appService.getHello() + ' - User';
  }

  @Get('/admin')
  @Roles({ roles: ['realm:app-admin'] })
  getAdmin(): string {
    return this.appService.getHello() + ' - Admin';
  }

  @Get('/all')
  getAll(): string {
    return this.appService.getHello() + ' - All';
  }
}
