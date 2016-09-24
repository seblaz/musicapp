#!/bin/bash
echo "Creating Module: $1";
mkdir $1;
# assets
mkdir "$1/assets";
# models
echo "Creating Module Models";
mkdir "$1/models";
# views
echo "Creating Module Views";
mkdir "$1/views";
# Controllers
echo "Creating Module Controllers";
mkdir "$1/controllers";