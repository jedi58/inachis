#!/bin/bash
if [ $# -lt 2 ]
  then
        echo "Usage: ./generate_patch.sh NEW_TAG CURRENT_TAG [true]"
        echo "If true is specified a patch file will be generated in the format of patch_CURRENT_TAG_to_NEW_TAG.gz"
        exit 1
fi

echo "Release Notes ($2 to $1)"
git log --pretty=format:%s $2..$1
echo
echo Files:
git diff --name-only $1..$2

if [ $# -eq 3 ]
  then
        git diff-tree -r --no-commit-id --name-only --diff-filter=ACMRT $1..$2 | xargs tar -czf patch_$2_to_$1.tar.gz
fi
