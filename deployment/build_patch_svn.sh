#!/bin/bash
if [ $# -lt 2 ] ; then
	echo "Usage: ./svn_patch.sh REPOSITORY -rCURRENT_TAG:NEW_TAG [true]"
	echo "If true is specified a patch file will be generated in the format of patch-CURRENT_TAG_NEW_TAG.tar.gz"
	exit 1
fi
NEW_REV=${2//-r[0-9]*:/}
echo "Release Notes (r$NEW_REV)"
NOTES=$(svn log $1 $2)
echo "$NOTES"  | perl -pe 's/\n//g => s/^-.*/\n/g'
echo
echo "Files:"
FILE_LIST=$(svn diff $1 $2 --summarize)
if [[ "$FILE_LIST" != "" ]] ; then
	for i in $FILE_LIST
	do
		if [ "${i}" == "M" ] || [ "${i}" == "A" ] || [ "${i}" == "D" ] || [ "${i}" == "AM" ]; then
			LAST_ACTION="${i}"
			continue
		fi
		FILENAME="${i//$1/}"
		FN=${FILENAME##*/}
		FN="patch/${FILENAME//$FN/}"
		if [ "${LAST_ACTION}" == "M" ] || [ "${LAST_ACTION}" == "A" ] ; then
			echo $FILENAME
			if [ $# -eq 3 ] ; then
				if [[ "$FILENAME" == *".sql"* ]]; then
					touch patch_notes.txt
					echo "Run SQL: $FILENAME" >> patch_notes.txt
				fi
				if [ ! -d "$FN" ]; then
					mkdir -p $FN
				fi
				if [ "$1" != "$i" ]; then 
					svn export -r $NEW_REV --force $i patch/$FILENAME >> /dev/null 2>&1
					FN="$( cut -d '/' -f 1 <<< $FN )"
				fi
			fi
		elif [ "${LAST_ACTION}" == "D" ] ; then
			if [ $# -eq 3 ] ; then
				touch patch_notes.txt
				echo "Delete $FILENAME" >> patch_notes.txt
			else
				echo "Deleted $FILENAME"
			fi
		fi
	done
	if [ $# -eq 3 ] && [ "$FN" != "" ] ; then
		if [ -f "patch_notes.txt" ] && [ -d "patch/" ]; then
			mv patch_notes.txt patch/
		fi
		if [ -d "$FN" ]; then
			tar -czf patch${2//:/_to_}.tar.gz patch
			rm -rf $FN
			echo 
			echo "Patch created as: patch${2//:/_}.tar.gz"
		fi
	fi
fi
