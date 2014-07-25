<?php

if ($argc < 2) {
  exit("Usage ".$argv[0]." [inputfile.xml] \n");
}
$xmlFile = dirname(__FILE__) . '/' .$argv[1];

if(!is_file($xmlFile)){
  die("no file");
}

$xmlContent = simplexml_load_file($xmlFile);
if ($xmlContent->ruleset) {
  $xml = $xmlContent->ruleset->children();
}
else if ($xmlContent) {
  $xml = $xmlContent;
}
else {
  die("xml structure doesn`t fit. TODO: Fix script\n");
}

// process xml
$struct = processXml($xml, array(), '');

// AUSGABE
print substr(json_encode($struct),1,-1);


// ==========================================
// FUNCTIONS
// ==========================================

function processXml($xml, $result, $type = '', $itemName = '')
{
  if (empty($itemName)) {
    $main_attributes = $xml->attributes();
    $itemName = $main_attributes->name->__toString();
  }

  $structItem = array(
    "name" => $itemName
  );

  foreach ($xml as $xmlItem) {

    $item_attributes = $xmlItem->attributes();
    $itemName = $item_attributes->name ? $item_attributes->name->__toString() : "";

    $nodeType = $xmlItem->getName();

    if (!empty($itemName)) {

      $nextItem = array(
        "name" => $itemName
      );

      $contentType = getContentType($xmlItem, $nodeType);

      switch($contentType) {

        case "replicant":
          $attributes = $xmlItem->attributes();
          $repName = $attributes->name->__toString();
          $repAttributes = $xmlItem->replicant->attributes();
          $min = $repAttributes->min ? $repAttributes->min->__toString() : 0;
          $max = $repAttributes->max ? $repAttributes->max->__toString() : 0;

          if ($max) {
            $nextItem["name"] .= " ($min:$max)";
          }

          $innerElements = processXml($xmlItem->replicant->item, array(), $contentType, $repName);

          if (!array_key_exists("children", $nextItem)) {
            $nextItem["children"] = array();
          }
          array_push($nextItem["children"], $innerElements);

          if (!array_key_exists("children", $structItem)) {
            $structItem["children"] = array();
          }

          array_push($structItem["children"], $nextItem["children"][0][0]);
          $lastElementIdx = count($structItem["children"]) - 1;

          $structItem["children"][$lastElementIdx]["name"] = $nextItem["name"];

          break;

        case "container":
          if (!array_key_exists("children", $nextItem)) {
            $nextItem["children"] = array();
          }
          $attributes = $xmlItem->attributes();
          $min = $attributes->min ? $attributes->min->__toString() : 0;
          $max = $attributes->max ? $attributes->max->__toString() : 0;
          if ($max) {
            $nextItem["name"] .= " ($min:$max)";
          }

          $innerElements = processXml($xmlItem, array(), $contentType);

          array_push($nextItem["children"], $innerElements);

          if (!array_key_exists("children", $structItem)) {
            $structItem["children"] = array();
          }
          array_push($structItem["children"], $nextItem["children"][0][0]);
          $lastElementIdx = count($structItem["children"]) - 1;
          $structItem["children"][$lastElementIdx]["name"] = $nextItem["name"];
          break;

        default: 
          if (!array_key_exists("children", $structItem)) {
            $structItem["children"] = array();
          }
          $nextItem["name"] .= " : " . $contentType;
          $nextItem["size"] = rand(100,1000);
          array_push($structItem["children"], $nextItem);
      }
    }
  }

  array_push($result, $structItem);

  return $result;
}

function getContentType($item, $default = "")
{
  $type = $default;

  if ($type == "container") {
    return $type;
  }

  foreach ($item->children() as $child) {

    $childName = $child->getName();
    if ($childName !== "label" && $childName !== "description") {
      $type = $childName;
    }
  }

  return $type;
}

