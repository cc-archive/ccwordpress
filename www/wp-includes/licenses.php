<?php

class LicenseXML
{
    private $dom;

    # Loads licenses.xml
    function __construct($license_xml_filename) {
        $this->dom = domxml_open_file($license_xml_filename);
        $this->dom->xpath_init();
    }

    # Returns an array of jurisdiction arrays.
    # Each row in the array consists of {id, languages, uri}
    function getJurisdictions($launched="") {
        $jurisdictions = array();

        $xpath = $this->dom->xpath_new_context();
        if ($launched == "" or $launched == "all") {
            $result = $xpath->xpath_eval("//license-info/jurisdictions/jurisdiction-info");
        } else {
            $result = $xpath->xpath_eval("//license-info/jurisdictions/jurisdiction-info[@launched='$launched']");
        }

        foreach ($result as $j) {
            $jurisdiction = array();
            $jurisdiction_id = $j->get_attribute('id');
            $jurisdiction['id'] = $jurisdiction_id;

            foreach ($j->child_nodes() as $child) {
                if ($child->node_name() == 'languages') {
                    $jurisdiction['languages'] = $child->node_value();
                } else if ($child->node_name() == 'uri') {
                    $jurisdiction['uri'] = $child->node_value();
                }
            }

            $jurisdictions[$jurisdiction_id] = $jurisdiction;
        }
        return $jurisdictions;
    }

    # Returns an array of license arrays.
    # Each row in the array consists of {id, jurisdictions}
    function getLicenses($licenseclass='standard') {
        $licenses = array();

        # Query document
        $xpath = $this->dom->xpath_new_context();
        if ($licenseclass == '' or $licenseclass=='all') {
            $result = $xpath->xpath_eval("//licenseclass/license");
        } else {
            $result = $xpath->xpath_eval("//licenseclass[@id='$licenseclass']/license");
        }

        foreach ($result as $l) {
            $license = array();
            $license_id = $l->get_attribute('id');
            $license['id'] = $license_id;

            $jurisdictions = array();
            foreach ($l->child_nodes() as $j) {
                if ($j->node_name() == 'jurisdiction') {
                    $jurisdiction_id = $j->get_attribute('id');
                    if ($jurisdiction_id != '') {
                        array_push($jurisdictions, $jurisdiction_id);
                    }
                }
            }
            $license['jurisdictions'] = $jurisdictions;
            
            $licenses[$license_id] = $license;
        }
        return $licenses;
    }
    
    # Get a listing of the most current versions of all licenses for given jurisdiction
    function getLicensesCurrent($jurisdiction='-', $licenseclass='standard') {
        $licenses = $this->getLicenses($licenseclass);
        $current_licenses = array();
        
        foreach ($licenses as $license) {
            $license_id = $license['id'];

            $current_version = 0;
            $xpath = $this->dom->xpath_new_context();
            if ($jurisdiction == '' or $jurisdiction == 'all') {
                $versions = $xpath->xpath_eval("//licenseclass/license[@id='$license_id']/jurisdiction/version");
            } else {
                $versions = $xpath->xpath_eval("//licenseclass[@id='$licenseclass']/license[@id='$license_id']/jurisdiction[@id='$jurisdiction']/version");
            }
            foreach ($versions as $version) {
                $version_id = $version->get_attribute("id");
                if ($version_id > $license['version']) {
                    $license['version'] = $version_id;
                    $license['uri'] = $version->get_attribute("uri");
                }
            }
            if ($license['version'] > 0) {
                $current_licenses[$license_id] = $license;
            }
        }
        return $current_licenses;
    }
}

/***
 * Commandline routine for checking licenses.xml
 ***

 * Usage:
 *  $ php licenses.php jurisdictions
 *  $ php licenses.php licenses
 *  $ php licenses.php licenses_current
 * 
$command = $argv[1];

$license_xml = new LicenseXml("../license_xsl/licenses.xml");

switch($command) {
    case "jurisdictions":
        $launched = "all";
        foreach ($license_xml->getJurisdictions($launched) as $j) {
            printf("%-10s %-21s %s\n", $j['id'], $j['languages'], $j['uri']);
        }
        break;
    case "licenses":
        $licenses = $license_xml->getLicenses("all");
        foreach ($licenses as $license) {
            printf("%-12s %s\n", $license['id'], join(",", $license['jurisdictions']));
        }
        $license_xml->printLicenses("all");
        break;
    case "licenses_current":
        $jurisdiction="-";
        $licenseclass="standard";
        print("Jurisdiction: $jurisdiction\n");
        foreach ($license_xml->getLicensesCurrent($jurisdiction, $licenseclass) as $license) {
            printf("  %-12s %-12s %s\n", $license['id'], $license['version'], $license['uri']);
        }
        break;
    default:
        print "Usage:  $argv[0] [jurisdictions|licenses|licenses_current]\n";
}

exit(0);

***/

?>
