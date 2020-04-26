# Test Ximdex
> Calculation of benefits

## Table of contents
* [General info](#general-info)
* [Requirements](#requirements)
* [Setup](#setup)
* [Code Examples](#code-examples)
* [Features](#features)
* [Status](#status)
* [Contact](#contact)

## General info
This project is a technical test to be able to be part of the Ximdex team. It will assess the methodology followed and problem solving.

## Requirements
* PHP - version > 5.6
* Apache2 - version 2.0
* PHP Command Line Interpreter

## Setup
If Linux SO, after PHP and Apache2 installation, you also need to install PHP Command Line Interpreter:

`# apt-get install php5-cli (Debian and alike System)`

`# yum install php-cli (CentOS and alike System)`

## Code Examples
Examples of usage: `php test.php lib/includes/ventas.csv lib/includes/precios.json`

The first param is the executable file, the second and third params are the sales and prices files. Order of the two files not matter.

## Features
* Show benefits for category products

To-do list:
* Refactor `Sales::getTotal()` to optimize

## Status
Project is: _in progress_

## Contact
Created by [@jjlazo79](https://joselazo.es/) - feel free to contact me!
