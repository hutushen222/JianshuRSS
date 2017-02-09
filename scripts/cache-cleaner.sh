#!/bin/bash

echo "Start cache cleaning ..."

DIR="$(readlink -m $0 | xargs dirname | xargs dirname)/storage/cache/$(date -d 'yesterday' '+%Y%m%d')/"

echo "Dir is: $DIR"

if [[ -d $DIR ]]; then
  if [[ -w $DIR ]]; then
    rm -rf $DIR
    echo "Done"
  else
    echo "FAILED: permission deny"
  fi
else
  echo "FAILED: dir not exists"
fi
