#!/bin/sh
set -e

(git push) || true

git checkout master
git merge hml

git push origin master

git checkout hml
