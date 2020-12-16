<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Project
Rest Server Up-Send App. This application is used for the management of attendance events.

---

## How to install

---

## Tech Stack and Third Library
- Laravel ^7.0 ([docs](https://laravel.com/))
- JWT-Auth ^1.0 ([docs](https://jwt-auth.readthedocs.io/en/develop/))
- Endroid/Qr-Code ^3.9 ([docs](https://github.com/endroid/qr-code))

---

## Endpoint Rest API

|  No  | Endpoint Name   |    URL    | Status  | Deskripsi | `Token` |
| ---- |:---------------:|:---------:|:-------:|:---------:|:------:|
|  1   | Registration | [/api/auth/register](http://127.0.0.1:8000/api/auth/register) | `Aktif` | User Registration | - |
|  2   | Login | [/api/auth/login](http://127.0.0.1:8000/api/auth/login) | `Aktif` | User Login | - |
|  3   | Logout | [/api/auth/logout](http://127.0.0.1:8000/api/auth/logout) | `Aktif` | User Logout | - |
|  4   | List Event | [/api/event/](http://127.0.0.1:8000/api/event/) | `Aktif` | List of Event | `jwt` |
|  5   | Create Event | [/api/event/create/](http://127.0.0.1:8000/api/event/create) | `Aktif` | Create Event | `jwt` |
|  6   | Update Event | [/api/event/update/](http://127.0.0.1:8000/api/event/update) | `Aktif` | Update Event | `jwt` |
|  7   | Show Detail Event | [/api/event/show/](http://127.0.0.1:8000/api/event/show) | `Aktif` | Show Detail Event | `jwt` |
|  8   | Delete Event | [/api/event/delete/](http://127.0.0.1:8000/api/event/delete) | `Aktif` | Delete Event | `jwt` |
|  9   | Search Event | [/api/event/search/](http://127.0.0.1:8000/api/event/search) | `Aktif` | Search Event | `jwt` |
|  10   | Event Registration | [/api/event/registration/](http://127.0.0.1:8000/api/event/registration) | `Aktif` | Event Registration | `jwt` |
|  11   | Come to the Event | [/api/event/come/](http://127.0.0.1:8000/api/event/come) | `Aktif` | Come to the Event | `jwt` |
|  12   | List of participant | [/api/event/participant/](http://127.0.0.1:8000/api/event/participant/) | `Aktif` | list of participant who registered to the event | `jwt` |
|  13   | List of participant | [/api/event/participant/come/](http://127.0.0.1:8000/api/event/participant/come/) | `Aktif` | list of participant who came to the event | `jwt` |


---

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
