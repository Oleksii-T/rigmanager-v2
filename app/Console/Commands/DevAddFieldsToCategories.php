<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Category;
use App\Models\Translation;
use Illuminate\Console\Command;

class DevAddFieldsToCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:add-fields-to-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dev';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $categs = Category::whereNull('category_id')->get();
        $allCategoriesNames = '';

        foreach ($categs as $i => $c) {
            $allCategoriesNames .= $c->name . "\n";

            $childs = $c->childs;

            $this->addFieldsForCategory($c, $c->name);

            foreach ($childs as $child) {
                $name = $c->name . ' > ' . $child->name;
                $allCategoriesNames .= $name . "\n";

                $childs2 = $child->childs;

                $this->addFieldsForCategory($child, $name);

                foreach ($childs2 as $child2) {
                    $name = $c->name . ' > ' . $child->name . ' > ' . $child2->name;
                    $allCategoriesNames .= $name . "\n";

                    $this->addFieldsForCategory($child2, $name);
                }
            }
        }

        return 0;
    }

    private function addFieldsForCategory($c, $name)
    {
        // return;
        $fieldsConfig = $this->getFieldsConfig();
        $found = false;

        foreach ($fieldsConfig as $fieldCategory => $fields) {
            if (strtolower($fieldCategory) ==  strtolower($name)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->error("$c->id: $name - NOT FOUND");
            return;
        }

        $fields = str_replace(' ', '', $fields);
        $fields = explode("\n", $fields);
        $fields = array_map(fn ($f) => "$f: _____", $fields);
        $fields = implode("\n", $fields);

        Translation::updateOrCreate(
            [
                'translatable_id' => $c->id,
                'translatable_type' => Category::class,
                'field' => 'suggestions',
                'locale' => 'en',
            ],
            [
                'value' => $fields,
            ]
        );

        $this->line("$c->id: $name - created fields!");
    }

    private function getFieldsConfig()
    {
        return [
            'Rig & Accessories > Hook Block' =>
                'Load Capacity
                Hook Type
                Sheave Diameter
                Number of Sheaves
                Material',
            'Rig & Accessories > Swivel' =>
                'Pressure Rating
                Connection Type
                Rotation Speed
                Load Capacity
                Sealing Type',
            'Rig & Accessories > Rotary Table' =>
                'Table Size
                Maximum Load
                Rotation Speed
                Drive Type
                Gear Ratio',
            'Rig & Accessories > Kelly Valve' =>
                'Valve Size
                Pressure Rating
                Connection Type
                Material
                Activation Method',
            'Rig & Accessories > Draw works' =>
                'Power Rating
                Drum Size
                Line Speed
                Brake System
                Gear System',
            'Rig & Accessories > Hydraulic Power Unit' =>
                'Power Output
                Pressure Capacity
                Flow Rate
                Fuel Type
                Reservoir Capacity',
            'Rig & Accessories > Air Winch' =>
                'Load Capacity
                Line Speed
                Drum Size
                Air Pressure
                Control Type',
            'Rig & Accessories > Hydraulic Winch' =>
                'Load Capacity
                Line Speed
                Drum Size
                Hydraulic Pressure
                Control Type',
            'Rig & Accessories > Generator Set & Spare Parts' =>
                'Power Output
                Fuel Type
                Efficiency
                Noise Level
                Size and Weight',
            'Rig & Accessories > Bucking Unit' =>
                'Torque Capacity
                Pipe Diameter Range
                Rotation Speed
                Control System
                Power Source',
            'Rig & Accessories > Weight Indicator' =>
                'Measurement Range
                Accuracy
                Display Type
                Sensitivity
                Durability',
            'Rig & Accessories > BOP Testing Unit' =>
                'Pressure Capacity
                Flow Rate
                Test Medium
                Control System
                Portability',
            'Rig & Accessories > Top Drive Drilling System' =>
                'Torque Capacity
                Speed Range
                Power Rating
                Connection Size
                Control System',
            'Rig & Accessories > Deadline Anchor' =>
                'Load Capacity
                Cable Diameter
                Material
                Adjustment Mechanism
                Safety Features',
            'Rig & Accessories > Drilling Hose' =>
                'Hose Diameter
                Pressure Rating
                Material
                Length
                Connection Type',
            'Rig & Accessories > Wire Rope / Wireline' =>
                'Diameter
                Tensile Strength
                Material
                Core Type
                Lay Direction',
            'Rig & Accessories > Cementing Unit' =>
                'Pumping Rate
                Pressure Capacity
                Tank Volume
                Mixing Capacity
                Control System',
            'Rig & Accessories > Mast' =>
                'Height
                Load Capacity
                Material
                Base Design
                Wind Load Rating',
            'Rig & Accessories > Substructure' =>
                'Load Capacity
                Height
                Material
                Design Type
                Deck Area',
            'Rig & Accessories > Mobile Drilling Rig' =>
                'Drilling Depth
                Power Rating
                Mobility Type
                Footprint Size
                Weight',
            'Rig & Accessories > Cat Walks' =>
                'Load Capacity
                Width
                Material
                Surface Treatment
                Safety Features',
            'Rig & Accessories > Monkey Board' =>
                'Height Adjustment
                Load Capacity
                Material
                Safety Features
                Accessibility',
            'Rig & Accessories > Dog House' =>
                'Size
                Layout
                Insulation
                Material
                Additional Features',
            'Rig & Accessories > Rails' =>
                'Length
                Material
                Load Capacity
                Mounting Type
                Safety Features',
            'Rig & Accessories > Rig Up & Down System' =>
                'Lifting Capacity
                Speed
                Power Source
                Control System
                Safety Mechanisms',


            'Well Control Equipment > IBOP' =>
                'Pressure Rating
                Connection Size
                Activation Method
                Material
                Length',
            'Well Control Equipment > BOP Control Unit' =>
                'Control Type (Hydraulic, Electric, etc.)
                Pressure Capacity
                Number of Control Stations
                Fluid Capacity
                System Integration',
            'Well Control Equipment > Drill Pipe Float Valve' =>
                'Size
                Pressure Rating
                Valve Type (Flapper, Dart, etc.)
                Material
                Flow Area',
            'Well Control Equipment > Annular BOP' =>
                'Bore Size
                Pressure Rating
                Closing Ratio
                Operating Temperature
                Material',
            'Well Control Equipment > Ram BOP' =>
                'Ram Type (Pipe, Blind, Shear, etc.)
                Bore Size
                Pressure Rating
                Operating Temperature
                Actuation Method (Hydraulic, Manual)',
            'Well Control Equipment > Drilling Spool' =>
                'Pressure Rating
                Bore Size
                Connection Type
                Material
                Height',
            'Well Control Equipment > Accumulator Unit' =>
                'Capacity
                Pressure Rating
                Accumulator Type (Bladder, Piston, etc.)
                Control System
                Fluid Type',
            'Well Control Equipment > Choke Manifold / Kill Manifold' =>
                'Pressure Rating
                Number of Chokes
                Choke Type (Adjustable, Positive, etc.)
                Connection Size
                Material',
            'Well Control Equipment > Lubricator' =>
                'Pressure Rating
                Size (Length and Diameter)
                Connection Type
                Material
                Seal Type',
            'Well Control Equipment > BOP Parts' =>
                'Specific to the BOP Type (Annular, Ram, etc.)
                Seals and Gaskets
                Actuation Components
                Locking Mechanisms
                Wear Parts (Ram Blocks, Faces, etc.)',
            'Well Control Equipment > Flare System' =>
                'Flare Type (Elevated, Ground, etc.)
                Burner Capacity
                Ignition System
                Emission Controls
                Flare Stack Height',
            'Well Control Equipment > Lines' =>
                'Material
                Diameter
                Pressure Rating
                Connection Type
                Length',
            'Well Control Equipment > Valves & Wheels' =>
                'Valve Type (Gate, Ball, Check, etc.)
                Pressure Rating
                Size
                Actuation Method (Manual, Hydraulic, etc.)
                Material',


            'Solid Control Equipment > Shale Shaker' =>
                'Screen Area
                G-Force
                Motor Power
                Deck Angle Adjustment
                Throughput Capacity',
            'Solid Control Equipment > Mud Cleaner' =>
                'Screen Size
                Processing Capacity
                Hydrocyclone Size
                Number of Hydrocyclones
                Motor Power',
            'Solid Control Equipment > Desander' =>
                'Cone Size
                Number of Cones
                Processing Capacity
                Inlet and Outlet Diameter
                Operating Pressure',
            'Solid Control Equipment > Desilter' =>
                'Cone Size
                Number of Cones
                Processing Capacity
                Inlet and Outlet Diameter
                Operating Pressure',
            'Solid Control Equipment > Vacuum Degasser' =>
                'Processing Capacity
                Vacuum Level
                Motor Power
                Tank Volume
                Inlet and Outlet Size',
            'Solid Control Equipment > Decanter Centrifuge' =>
                'Bowl Diameter and Length
                Max Rotational Speed
                Processing Capacity
                Drive Type (Hydraulic, Electric)
                Material of Construction',
            'Solid Control Equipment > Mud Gas Separator' =>
                'Diameter and Height
                Operating Pressure
                Gas Venting Capacity
                Liquid Seal Type
                Material of Construction',
            'Solid Control Equipment > Sand Pump' =>
                'Flow Rate
                Head
                Motor Power
                Impeller Size
                Material of Construction',
            'Solid Control Equipment > Mud Agitator' =>
                'Motor Power
                Impeller Size and Type
                Shaft Length
                Speed
                Mounting Configuration',
            'Solid Control Equipment > Mud Tank' =>
                'Volume Capacity
                Material of Construction
                Tank Type (Open, Closed, etc.)
                Compartmentalization
                Dimensions',
            'Solid Control Equipment > Mud Gun' =>
                'Nozzle Size
                Pressure Rating
                Flow Rate
                Orientation (Fixed, Swivel)
                Connection Type',
            'Solid Control Equipment > Shaker Screen' =>
                'Mesh Size
                Screen Type (Hook Strip, Frame, etc.)
                Dimensions
                Conductance
                Material',
            'Solid Control Equipment > Hydrocyclone' =>
                'Size (Diameter)
                Material
                Pressure Drop
                Flow Rate
                Cut Point',
            'Solid Control Equipment > Hoses' =>
                'Diameter
                Length
                Pressure Rating
                Material
                Connection Type',
            'Solid Control Equipment > Sludge Pump' =>
                'Flow Rate
                Head
                Motor Power
                Type (Centrifugal, Positive Displacement)
                Material of Construction',
            'Solid Control Equipment > Filters' =>
                'Filter Type (Cartridge, Bag, etc.)
                Micron Rating
                Material
                Flow Rate
                Size and Dimensions',
            'Solid Control Equipment > Spare Parts' =>
                'Specific to the Equipment Type
                Wear Parts (Screens, Gaskets, Seals)
                Mechanical Components (Bearings, Motors)
                Electrical Components (Switches, Cables)
                Hydraulic Components (Valves, Pumps)',




            'Drill String > Square Kelly / Hexagonal Kelly' =>
                'Length
                Size and Shape (Square or Hexagonal)
                Material
                Connection Type
                Tensile Strength',
            'Drill String > Drill Pipe (DP)' =>
                'Steel Grade
                Wall Thickness
                Outer Diameter
                Length
                Tool Joint Type',
            'Drill String > Heavy Weight Drill Pipe(HWDP)' =>
                'Wall Thickness
                Outer Diameter
                Steel Grade
                Length
                Central Upset Area',
            'Drill String > Drill Collar (DC)' =>
                'Material
                Outer and Inner Diameter
                Length
                Bending Strength Ratio
                Slick or Spiral Groove',
            'Drill String > Stabilizer' =>
                'Blade Type
                Outer Diameter
                Length
                Material
                Connection Type',
            'Drill String > Stabilizer > Blade Stabilizer' =>
                'Number of Blades
                Blade Width
                Material
                Outer Diameter
                Connection Type',
            'Drill String > Stabilizer > Spiral-blade Stabilizer' =>
                'Spiral Angle
                Blade Width
                Material
                Outer Diameter
                Length',
            'Drill String > Stabilizer > Pneumopercussion Stabilizer' =>
                'Operating Pressure
                Hammer Type
                Outer Diameter
                Length
                Impact Frequency',
            'Drill String > Stabilizer > Expanding Stabilizer' =>
                'Expansion Range
                Mechanism Type
                Outer Diameter
                Length
                Material',
            'Drill String > Stabilizer > Roller Stabilizer' =>
                'Roller Type
                Outer Diameter
                Roller Material
                Bearing Type
                Length',
            'Drill String > Stabilizer > Cones & Rollers for Stabilizer' =>
                'Size and Type
                Material
                Bearing Type
                Replacement Ease
                Durability',
            'Drill String > Stabilizer > PDC Stabilizer' =>
                'PDC Cutter Size and Type
                Blade Count
                Material
                Outer Diameter
                Length',
            'Drill String > Centralizer' =>
                'Type (Bow-Spring, Rigid, etc.)
                Size
                Material
                Restoring Force
                Collapse Strength',
            'Drill String > Filters' =>
                'Type (Mesh, Cartridge, etc.)
                Micron Rating
                Material
                Flow Capacity
                End Connection Type',
            'Drill String > Hole Opener' =>
                'Diameter Size
                Cutter Type
                Number of Cutters
                Connection Type
                Material',
            'Drill String > Drilling Motor' =>
                'Type (Mud Motor, Electric, etc.)
                Power Output
                Speed Range
                Connection Type
                Length',
            'Drill String > Lifting Sub' =>
                'Length
                Connection Type
                Load Capacity
                Material
                Outer Diameter',
            'Drill String > Drill Subs' =>
                'Type (Crossover, Saver, etc.)
                Length
                Outer Diameter
                Connection Type
                Material',
            'Drill String > Drifts' =>
                'Diameter
                Length
                Material
                Type (Cylinder, Tapered, etc.)
                Compliance with Pipe Size',
            'Drill String > Drill Bit' =>
                'Type (Roller Cone, Fixed Cutter, etc.)
                Size
                IADC Code
                Nozzle Size and Number
                Connection Type',
            'Drill String > Drill Bit > Bicentric Bits' =>
                'Diameter Range
                Cutting Structure (Type of Teeth or Cutters)
                Drilling Fluid Flow Rate Compatibility
                Application (Formation Type)
                Connection Type',
            'Drill String > Drill Bit > Bit Breakers' =>
                'Size Compatibility with Drill Bits
                Material and Durability
                Teeth or Lug Pattern
                Handling and Safety Features
                Ease of Bit Engagement and Disengagement',
            'Drill String > Drill Bit > Measuring Devices (Diameter)' =>
                'Measurement Range
                Accuracy and Resolution
                Material and Durability
                Compatibility with Various Drill Bit Types
                Ease of Use and Readability',
            'Drill String > Drill Bit > Wing Bits' =>
                'Number of Wings
                Cutting Structure (Type of Teeth or Cutters)
                Diameter Range
                Application (Formation Type)
                Fluid Circulation Characteristics',
            'Drill String > Drill Bit > Bit Nozzles' =>
                'Size and Flow Rate
                Material (e.g., Tungsten Carbide)
                Compatibility with Drill Bit Types
                Resistance to Erosion and Plugging
                Interchangeability and Customizability',
            'Drill String > Drill Bit > Pneumopercussion Bits' =>
                'Impact Energy and Frequency
                Diameter Range
                Airflow Requirements
                Cutting Structure and Material
                Application in Various Rock Formations',
            'Drill String > Drill Bit > Cone Bits' =>
               'Number of Cones
                Cutting Structure (Teeth or Tungsten Carbide Inserts)
                Bearing Type
                Diameter Range
                Application in Various Formations',
            'Drill String > Drill Bit > Carbide Bits' =>
                'Carbide Type and Grade
                Diameter Range
                Cutting Structure
                Application in Hard and Abrasive Formations
                Wear Resistance',
            'Drill String > Drill Bit > PDC Bits' =>
                'Cutter Size and Density
                Blade Count and Design
                Diameter Range
                Application in Soft to Medium-Hard Formations
                Hydraulic Design for Cuttings Evacuation',
            'Drill String > Drill Bit > TSP Bits' =>
                'Cutter Size and Density
                Diameter Range
                Application in Hard and Abrasive Formations
                Thermal Stability and Wear Resistance
                Design Features for Enhanced Drilling Performance',
            'Drill String > Down Hole Motors' =>
                'Type (Rotational, Percussion, etc.)
                Power Source (Hydraulic, Pneumatic, etc.)
                Output Torque
                Speed Range
                Length and Diameter
                Flow Rate Compatibility',
            'Drill String > Down Hole Motors > Rotational' =>
                'Rotational Speed (RPM)
                Torque Output
                Diameter Compatibility with Borehole
                Power Source (e.g., Hydraulic, Electric)
                Durability and Temperature Resistance',
            'Drill String > Down Hole Motors > Percussion' =>
                'Impact Frequency and Energy
                Tool Length and Diameter
                Compatibility with Bit Types
                Power Source (e.g., Hydraulic, Pneumatic)
                Operational Depth Range',
            'Drill String > Down Hole Motors > Pneumatic' =>
                'Air Flow Rate and Pressure Requirements
                Torque and Speed Specifications
                Diameter and Length for Borehole Compatibility
                Environmental Suitability (Temperature, Pressure)
                Durability and Maintenance Requirements',
            'Drill String > Down Hole Motors > Electrical' =>
                'Power and Voltage Requirements
                Speed and Torque Output
                Diameter and Length
                Temperature and Pressure Rating
                Protection Features (e.g., Overload Protection)',
            'Drill String > Down Hole Motors > Hydraulic' =>
                'Hydraulic Fluid Flow and Pressure Requirements
                Torque and Speed Output
                Diameter and Length
                Efficiency and Power Output
                Compatibility with Drilling Fluids',
            'Handling Tools > Drilling Elevator' =>
                'Load Capacity
                Size Range
                Type (Single Joint, Bottle Neck, etc.)
                Material
                Safety Features',
            'Handling Tools > Elevator Link' =>
                'Load Capacity
                Length
                Connection Type
                Material
                Safety Factor',
            'Handling Tools > Manual Tong' =>
                'Size Range
                Torque Rating
                Handle Length
                Jaw Design
                Material',
            'Handling Tools > Slip' =>
                'Size Range
                Load Capacity
                Type (Rotary, Drill Collar, Casing, etc.)
                Material
                Grip Pattern',
            'Handling Tools > Safety Clamp' =>
                'Size Range
                Type (Slip Type, C Clamp, etc.)
                Material
                Load Capacity
                Grip Pattern',
            'Handling Tools > Power Tong' =>
                'Torque Rating
                Size Range
                Speed Range
                Control System
                Jaw Design',
            'Handling Tools > Spider' =>
                'Load Capacity
                Size Range
                Type (Pneumatic, Hydraulic, Mechanical)
                Material
                Slip Design',
            'Handling Tools > Stabbing Guide' =>
                'Pipe Size Range
                Material
                Durability
                Ease of Installation
                Protection Efficiency',
            'Handling Tools > Pipe Wiper' =>
                'Diameter
                Material
                Wiping Efficiency
                Wear Resistance
                Compatibility with Fluids',
            'Handling Tools > Sucker Rod Tools' =>
                'Size Range
                Material
                Tool Type (Wrench, Elevator, etc.)
                Load Capacity
                Operational Efficiency',
            'Handling Tools > Quick Release Thread Protector' =>
                'Size Range
                Connection Type
                Material
                Release Mechanism
                Durability',
            'Handling Tools > Lifting Cap' =>
                'Load Capacity
                Connection Type
                Material
                Size
                Safety Features',
            'Handling Tools > Casing Bushing and Insert Bowls' =>
                'Size Range
                Load Capacity
                Material
                Type (Rotary, Slip, etc.)
                Compatibility with Casing Sizes',
            'Handling Tools > Casing Spider and Insert Bowl' =>
                'Load Capacity
                Size Range
                Type (Pneumatic, Hydraulic, Mechanical)
                Material
                Operational Efficiency',
            'Handling Tools > Roller Kelly Bushing' =>
                'Load Capacity
                Size Range
                Type (Square, Hexagonal, etc.)
                Material
                Roller Type',
            'Handling Tools > Rotary Table Bushing and Insert' =>
                'Size Range
                Load Capacity
                Material
                Compatibility with Rotary Table
                Durability',
            'Handling Tools > Bowls' =>
                'Size Range
                Material
                Type (Slip, Casing, etc.)
                Load Capacity
                Compatibility with Other Tools',
            'Handling Tools > Dies' =>
                'Size and Type
                Material
                Grip Pattern
                Compatibility with Tongs
                Durability',
            'Handling Tools > Fill In Circulate Tools' =>
                ' Size Range
                Material
                Operational Efficiency
                Compatibility with Drilling Fluids
                Pressure Rating',
            'Handling Tools > Iron Roughneck' =>
                'Torque Capacity
                Speed
                Size Range
                Control System
                Safety Features',
            'Handling Tools > Spinning Wrenches' =>
                'Torque Rating
                Size Range
                Speed Range
                Control System
                Material',
            'Handling Tools > Elevator' =>
                'Load Capacity
                Size Range
                Type (Single Joint, Sucker Rod, etc.)
                Material
                Locking Mechanism',
            'Handling Tools > Hydraulic Tong' =>
                'Torque Rating
                Size Range
                Control System
                Material
                Operational Speed',
            'Handling Tools > Hydraulic Tong Control Unit' =>
                'Control Type (Manual, Automatic)
                Power Source
                Operational Pressure
                Compatibility with Tongs
                Control Features',
            'Handling Tools > Tubing Tong' =>
                'Torque Rating
                Size Range
                Jaw Design
                Control System
                Material',
            'Handling Tools > Casing Tong' =>
                'Torque Rating
                Size Range
                Jaw Design
                Control System
                Material',
            'Handling Tools > Forks' =>
                'Load Capacity
                Material
                Type (Pipe, Tubing, etc.)
                Size Range,
                Durability',
            'Handling Tools > Pipe Spanner' =>
                'Size Range
                Material
                Type (Adjustable, Fixed, etc.)
                Grip Pattern
                Durability',
            'Handling Tools > Chain Spanner' =>
                'Chain Length
                Material
                Grip Capacity
                Durability
                Operational Efficiency',
            'Handling Tools > Hinged Tong' =>
                'Torque Rating
                Size Range
                Material
                Hinge Mechanism
                Operational Efficiency',




            'Downhole Tools > Casing Centralizer' =>
                'Size Range
                Type (Bow-Spring, Rigid, etc.)
                Material
                Bow Height
                Restoring Force',
            'Downhole Tools > Cementing Plug' =>
                'Size (Diameter)
                Material
                Type (Top, Bottom, etc.)
                Pressure Rating
                Drillability',
            'Downhole Tools > Float Collar & Float Shoe' =>
                'Size (Diameter)
                Material
                Valve Type
                Pressure Rating
                Connection Type',
            'Downhole Tools > External Hook' =>
                'Load Capacity
                Size Range
                Material
                Type (Slip, Basket, etc.)
                Latching Mechanism',
            'Downhole Tools > Internal Hook' =>
                'Load Capacity
                Size Range
                Material
                Type (Bail, Spear, etc.)
                Latching Mechanism',
            'Downhole Tools > Casing Cup Tester' =>
                'Size Range
                Pressure Rating
                Cup Material
                Connection Type
                Testing Range',
            'Downhole Tools > Fishing Tools' =>
                'Specific to Each Fishing Tool Type
                Size Range
                Material
                Type (Magnet, Jar, Overshot, etc.)
                Operational Function',
            'Downhole Tools > Fishing Tools > Fishing Magnet' =>
                'Size and Strength
                Material
                Magnetic Type (Permanent, Electro, etc.)
                Retrieval Capacity
                Operational Depth',
            'Downhole Tools > Fishing Tools > Fishing Jar' =>
                'Jar Type (Hydraulic, Mechanical, etc.)
                Size Range
                Impact Force
                Material
                Operational Depth',
            'Downhole Tools > Fishing Tools > Drift' =>
                'Diameter
                Length
                Material
                Type (Barrel, Tapered, etc.)
                Compatibility with Tubulars',
            'Downhole Tools > Fishing Tools > Junk Mills' =>
                'Mill Type (Flat Bottom, Concave, etc.)
                Size Range
                Material
                Cutting Structure
                Connection Type',
            'Downhole Tools > Fishing Tools > Junk Basket' =>
                'Size Range
                Basket Type
                Material
                Retrieval Capacity
                Filtration Size',
            'Downhole Tools > Fishing Tools > Die Collar' =>
                'Size Range
                Material
                Thread Type
                Gripping Mechanism
                Durability',
            'Downhole Tools > Fishing Tools > Taper Tap' =>
                'Size Range
                Taper Angle
                Material
                Thread Type
                Gripping Mechanism',
            'Downhole Tools > Fishing Tools > Junk Sub' =>
                'Size Range
                Material
                Basket Type
                Retrieval Capacity
                Connection Type',
            'Downhole Tools > Fishing Tools > Overshot' =>
                'Size Range
                Material
                Grasping Mechanism
                Load Capacity
                Connection Type',
            'Downhole Tools > Fishing Tools > Releasing Spear' =>
                'Size Range
                Material
                Release Mechanism
                Load Capacity
                Connection Type',
            'Downhole Tools > Stop Collar' =>
                'Size (Diameter)
                Material
                Type (Slip-On, Hinged, etc.)
                Holding Force
                Compatibility with Centralizer',
            'Downhole Tools > Casing Scraper' =>
                'Size Range
                Material
                Scraper Type
                Connection Type
                Cleaning Efficiency',
            'Downhole Tools > Milling Shoes' =>
                'Shoe Type (Flat Bottom, Concave, etc.)
                Size Range
                Material
                Cutting Structure
                Connection Type',
            'Downhole Tools > Casing Cutter' =>
                'Cutting Range
                Blade Type
                Material
                Operational Depth
                Connection Type',
            'Downhole Tools > Jars' =>
                'Jar Type (Hydraulic, Mechanical, etc.)
                Size Range
                Impact Force
                Material
                Operational Depth',


            'Mud Pump & Spare Parts > Mud Pump' =>
                'Maximum Pressure
                Displacement (Flow Rate)
                Power Rating
                Liner Size
                Stroke Length',
            'Mud Pump & Spare Parts > Centrifugal Pump' =>
                'Flow Rate
                Head (Pressure)
                Efficiency
                Impeller Size
                Power Requirement',
            'Mud Pump & Spare Parts > Plunger Pump' =>
                'Plunger Diameter
                Stroke Length
                Pressure Rating
                Flow Rate
                Power Source',
            'Mud Pump & Spare Parts > Sinking Pump' =>
                'Flow Rate
                Head (Pressure)
                Submergence Depth
                Material
                Motor Power',
            'Mud Pump & Spare Parts > Mud Pump Unit' =>
                'Total Power Output
                Number of Pumps
                Skid or Trailer Mounted
                Control System
                Auxiliary Equipment',
            'Mud Pump & Spare Parts > Mud Pump Liner' =>
                'Material
                Inner Diameter
                Length
                Compatibility with Pump Model
                Wear Resistance',
            'Mud Pump & Spare Parts > Mud Pump Piston' =>
                'Diameter
                Material
                Seal Type
                Compatibility with Liner
                Durability',
            'Mud Pump & Spare Parts > Mud Pump Valve & Seat' =>
                'Valve Type
                Material
                Size
                Pressure Rating
                Compatibility with Pump Model',
            'Mud Pump & Spare Parts > Pulsation Dampener' =>
                'Capacity
                Pressure Rating
                Pre-charge Type
                Material
                Connection Type',
            'Mud Pump & Spare Parts > Fluid End Module' =>
                'Material
                Pressure Rating
                Compatibility with Pump Model
                Component Includes (Valves, Seats, etc.)
                Design Features',
            'Mud Pump & Spare Parts > Hydraulic Components' =>
                'Component Type (Pumps, Motors, etc.)
                Pressure Rating
                Compatibility with Pump System
                Material
                Performance Specifications',
            'Mud Pump & Spare Parts > Engine' =>
                'Power Output
                Fuel Type
                Efficiency
                Emission Standards
                Compatibility with Pump Unit',
            'Mud Pump & Spare Parts > Gear box' =>
                'Gear Ratio
                Power Rating
                Type (Helical, Bevel, etc.)
                Material
                Compatibility with Pump',
            'Mud Pump & Spare Parts > Control Unit' =>
                'Control Type (Manual, Automated)
                Interface
                Compatibility with Mud Pump
                Safety Features
                Monitoring Capabilities',
            'Mud Pump & Spare Parts > Filters' =>
                'Type (Air, Oil, etc.)
                Micron Rating
                Compatibility with Pump
                Material
                Replacement Frequency',
            'Mud Pump & Spare Parts > Safety Valves' =>
                'Pressure Rating
                Valve Type (Relief, Check, etc.)
                Material
                Connection Size
                Compatibility with Pump',
            'Mud Pump & Spare Parts > Cooling System' =>
                'Type (Air, Water, etc.)
                Capacity
                Compatibility with Pump
                Components Included (Radiators, Fans, etc.)
                Maintenance Requirements',
            'Mud Pump & Spare Parts > Spare Parts' =>
                'Specific to Pump Model
                Parts Included (Seals, Bearings, etc.)
                Material
                Replacement Frequency
                Compatibility with Existing System',





            'Production Equipment & OCTG > Pumping Unit' =>
                'Load Capacity
                Stroke Length
                Speed (Strokes per Minute)
                Power Source (Electric, Hydraulic)
                Counterbalance System',
            'Production Equipment & OCTG > Sucker Rod' =>
                'Diameter
                Length
                Material (Steel, Fiberglass, etc.)
                Grade (Strength)
                Connection Type',
            'Production Equipment & OCTG > Sucker Rod Pump' =>
                'Barrel Size
                Stroke Length
                Pump Type (Stationary, Traveling)
                Valve Type
                Material',
            'Production Equipment & OCTG > Progressive Cavity Pump' =>
                'Flow Rate
                Pressure Rating
                Rotor/Stator Material
                Motor Type (Electric, Hydraulic)
                Viscosity Range',
            'Production Equipment & OCTG > Electric Submersible Pump' =>
                'Power Rating
                Flow Rate
                Head (Pressure)
                Motor Type
                Cable Type',
            'Production Equipment & OCTG > Sucker Rod Guide' =>
                'Size (Diameter)
                Material
                Rod Compatibility
                Wear Resistance
                Installation Method',
            'Production Equipment & OCTG > Casing Pipe' =>
                'Outer Diameter
                Wall Thickness
                Material Grade
                Thread Type
                Length',
            'Production Equipment & OCTG > Tubing' =>
                'Outer Diameter
                Wall Thickness
                Material Grade
                Thread Type
                Length',
            'Production Equipment & OCTG > Line Pipe' =>
                'Outer Diameter
                Wall Thickness
                Material Grade
                Coating Type
                Length',
            'Production Equipment & OCTG > Fiber Reinforced Plastic Pipe (FRP Pipe)' =>
                'Diameter
                Pressure Rating
                Material Composition
                Length
                Connection Type',
            'Production Equipment & OCTG > Screen Pipe' =>
                'Diameter
                Screen Type (Wire-Wrapped, Slotted, etc.)
                Material
                Slot Size
                Length',
            'Production Equipment & OCTG > Vacuum Insulated Tubing' =>
                'Outer Diameter
                Insulation Type
                Material
                Thermal Efficiency
                Length',
            'Production Equipment & OCTG > Pup Joint' =>
                'Length
                Diameter
                Material Grade
                Thread Type
                Pressure Rating',
            'Production Equipment & OCTG > Thread Protector' =>
                'Size
                Material (Plastic, Steel, etc.)
                Thread Compatibility
                Impact Resistance
                Reusability',
            'Production Equipment & OCTG > Thread Gauge' =>
                'Gauge Type
                Measurement Range
                Accuracy
                Material
                Compatibility with Thread Types',
            'Production Equipment & OCTG > Coupling' =>
                'Diameter
                Material
                Thread Type
                Grade
                Length',
            'Production Equipment & OCTG > Shoe' =>
                'Type (Float, Guide, etc.)
                Diameter
                Material
                Connection Type
                Pressure Rating',
            'Production Equipment & OCTG > Pipe Joints' =>
                'Type (Integral, Welded, etc.)
                Material
                Diameter
                Length
                Pressure Rating',
            'Production Equipment & OCTG > Crossovers' =>
                'Diameter
                Material
                Pressure Rating
                Connection Types
                Length',
            'Production Equipment & OCTG > Scratcher' =>
                'Diameter
                Material
                Type (Wire, Brush, etc.)
                Length
                Compatibility with Tubing/Casing',
            'Production Equipment & OCTG > Cementing Tools' =>
                'Type (Centralizers, Float Collars, etc.)
                Diameter
                Material
                Functionality
                Compatibility with Casing/Tubing',
            'Production Equipment & OCTG > Packers' =>
                'Type (Permanent, Retrievable)
                Pressure Rating
                Temperature Rating
                Size (Diameter)
                Material',




            'Wellhead Equipment > Casing Head' =>
                'Top and Bottom Connection Size and Type
                Pressure Rating
                Material
                Number of Outlets
                Load Capacity',
            'Wellhead Equipment > Tubing Head' =>
                'Connection Size and Type (Top and Bottom)
                Pressure Rating
                Material
                Bore Diameter
                Accessory Connections',
            'Wellhead Equipment > Christmas Tree' =>
                'Valve Configuration
                Pressure and Temperature Rating
                Connection Type
                Material
                Choke Type',
            'Wellhead Equipment > Blind Flange' =>
                'Diameter
                Pressure Rating
                Material
                Bolt Pattern
                Thickness',
            'Wellhead Equipment > Companion Flange' =>
                'Diameter
                Pressure Rating
                Material
                Connection Type
                Bolt Pattern',
            'Wellhead Equipment > Double Studded Adapter (DSA)' =>
                'Top and Bottom Flange Size
                Pressure Rating
                Material
                Stud and Nut Size
                Thickness',
            'Wellhead Equipment > Weld Neck Flange' =>
                'Diameter
                Pressure Rating
                Material
                Neck Type
                Welding Specification',
            'Wellhead Equipment > Tee & Cross' =>
                'Size and Type of Connections
                Pressure Rating
                Material
                Configuration (Tee or Cross)
                Thickness',
            'Wellhead Equipment > Mud Gate Valve' =>
                'Size
                Pressure Rating
                Material
                Connection Type
                Seat and Seal Type',
            'Wellhead Equipment > Gate Valve' =>
                'Size
                Pressure Rating
                Material
                Connection Type
                Operation Mechanism (Handwheel, Actuator)',
            'Wellhead Equipment > Check Valve' =>
                'Size
                Pressure Rating
                Material
                Connection Type
                Check Mechanism (Swing, Lift, etc.)',
            'Wellhead Equipment > Crossover Adapter' =>
                'Size and Type of Connections
                Pressure Rating
                Material
                Configuration
                Length',
            'Wellhead Equipment > Spacer Spool' =>
                'Length
                Top and Bottom Flange Size
                Pressure Rating
                Material
                Bore Size',



            'Flowline Products > Swivel Joint' =>
                'Size
                Pressure Rating
                Material
                End Connection Type
                Rotation Capability',
            'Flowline Products > Hose Loop' =>
                'Length
                Hose Diameter
                Pressure Rating
                Material
                End Connection Type',
            'Flowline Products > Integral Fitting' =>
                'Size
                Pressure Rating
                Material
                Connection Type
                Configuration (Elbow, Tee, etc.)',
            'Flowline Products > Integral Pup Joint' =>
                'Length
                Diameter
                Pressure Rating
                Material
                End Connection Type',
            'Flowline Products > Hammer Union' =>
                'Size
                Pressure Rating
                Material
                Connection Type
                Seal Type',
            'Flowline Products > Relief Valve' =>
                'Set Pressure
                Size
                Material
                Connection Type
                Flow Capacity',
            'Flowline Products > Check Valve' =>
                'Size
                Pressure Rating
                Material
                Connection Type
                Check Mechanism (Swing, Lift, etc.)',
            'Flowline Products > Plug Valve' =>
                'Size
                Pressure Rating
                Material
                Connection Type
                Operation Mechanism (Manual, Gear Operated)',



            'Laboratory of Mud Chemical-Analysis > Density Measurement Equipment' =>
                'Measurement Range
                Accuracy
                Resolution
                Method of Measurement (e.g., Mud Balance)
                Calibration Capabilities',
            'Laboratory of Mud Chemical-Analysis > ASG Measurement Equipment' =>
                'Measurement Range
                Accuracy
                Resolution
                Calibration Options
                Sample Volume Requirement',
            'Laboratory of Mud Chemical-Analysis > Viscosity Measurement Equipment' =>
                'Measurement Range (e.g., for different shear rates)
                Types of Viscosity Measured (e.g., Plastic, Apparent)
                Accuracy
                Temperature Control Features
                Data Output Options',
            'Laboratory of Mud Chemical-Analysis > SSS Measurement Equipment' =>
                'Measurement Range
                Accuracy
                Method of Detection (e.g., Centrifuge, Filtration)
                Sample Preparation Requirements
                Data Recording and Analysis Capabilities',
            'Laboratory of Mud Chemical-Analysis > pH Measurement Equipment' =>
                'Measurement Range
                Accuracy
                Resolution
                Calibration Capabilities
                Probe Type and Durability',
            'Laboratory of Mud Chemical-Analysis > Water Loss Measurement Equipment' =>
                'Measurement Technique (e.g., API Water Loss, Fluid Loss)
                Accuracy
                Pressure Range
                Sample Volume Capacity
                Temperature Control Options',



            'Others & Spare Parts > Additional Equipment & Electrics' =>
                'Equipment Type (e.g., lighting, electrical panels)
                Power Requirements
                Compliance Standards
                Durability and Environmental Resistance
                Compatibility with Existing Systems',
            'Others & Spare Parts > Camp Houses' =>
                'Size and Capacity
                Construction Material
                Amenities Included (e.g., HVAC, plumbing)
                Portability and Assembly Requirements
                Safety and Compliance Standards',
            'Others & Spare Parts > Lubricants' =>
                'Type (e.g., grease, hydraulic fluid)
                Viscosity Grade
                Temperature Range
                Compatibility with Equipment Materials
                Performance Specifications (e.g., wear protection, corrosion inhibition)',
            'Others & Spare Parts > Pneumatic System' =>
                'Components Included (e.g., compressors, valves, actuators)
                Pressure Rating
                Flow Capacity
                Material and Durability
                Compatibility with Existing Installations',
            'Others & Spare Parts > Power Supply System' =>
                'Power Output Capacity
                Source Type (e.g., generator, solar, battery)
                Voltage and Frequency Specifications
                Portability and Installation Requirements
                Safety and Efficiency Features',
            'Others & Spare Parts > Fuel Storage System' =>
                'Capacity
                Material and Construction
                Safety Features (e.g., overflow protection, venting systems)
                Compliance with Environmental Regulations
                Compatibility with Fuel Types',
            'Others & Spare Parts > Chemical Reagents' =>
                'Type and Purpose (e.g., drilling fluid additives)
                Purity and Composition
                Handling and Storage Requirements
                Safety Data and Usage Guidelines
                Compatibility with Drilling Operations',
            'Others & Spare Parts > Fire Safety System' =>
                'Components Included (e.g., extinguishers, alarms, sprinklers)
                Compliance with Safety Standards
                Coverage Area
                Maintenance Requirements
                Integration with Existing Safety Protocols',
            'Others & Spare Parts > Measurement Equipment' =>
                'Type of Equipment (e.g., gauges, meters)
                Measurement Parameters and Range
                Accuracy and Resolution
                Durability and Environmental Suitability
                Compatibility with Systems Being Measured',



            'Geo-Physical Borehole Survey > Coring Equipment' =>
                'Core Diameter and Length
                Material and Strength
                Coring Mechanism (e.g., Rotary, Wireline)
                Depth Capability
                Compatibility with Drilling Fluids',
            'Geo-Physical Borehole Survey > Coring Equipment > Coring Boxes' =>
                'Size and Capacity
                Material
                Seal and Locking Mechanism
                Portability
                Environmental Resistance',
            'Geo-Physical Borehole Survey > Coring Equipment > Core BBL' =>
                'Length and Diameter
                Material
                Inner Lining
                Recovery Efficiency
                Pressure Rating',
            'Geo-Physical Borehole Survey > Coring Equipment > Core Receivers' =>
                'Size and Capacity
                Material
                Pressure and Temperature Rating
                Core Preservation Features
                Seal Integrity',
            'Geo-Physical Borehole Survey > Coring Equipment > Coring Pipes' =>
                'Diameter and Length
                Material
                Thread Connection
                Strength and Flexibility
                Compatibility with Core Barrel',
            'Geo-Physical Borehole Survey > Coring Equipment > Coring Bits' =>
                'Size and Type
                Cutting Material (Diamond, Carbide, etc.)
                Drilling Efficiency
                Durability
                Compatibility with Core Barrel',
            'Geo-Physical Borehole Survey > Well Logging' =>
                'Logging Technologies Used (e.g., Electric, Sonic, etc.)
                Depth Measurement Capabilities
                Data Accuracy and Resolution
                Temperature and Pressure Rating
                Compatibility with Drilling Operations',
            'Geo-Physical Borehole Survey > Well Logging > Video Logging' =>
                'Camera Resolution and Quality
                Depth Capability
                Lighting and Visibility Features
                Data Transmission Method
                Environmental Resistance',
            'Geo-Physical Borehole Survey > Well Logging > Additional Equipment' =>
                'Specific to Logging Type (e.g., Calipers, Centralizers)
                Material
                Compatibility with Logging Tools
                Durability and Environmental Suitability
                Operational Efficiency',
            'Geo-Physical Borehole Survey > Well Logging > Continuous Directional Survey' =>
                'Measurement Accuracy
                Sensor Types (e.g., Gyroscopic, Magnetic)
                Data Transmission Speed
                Depth Capability
                Real-Time Data Processing',
            'Geo-Physical Borehole Survey > Well Logging > Caliper Log' =>
                'Measurement Range
                Accuracy
                Sensor Type
                Compatibility with Borehole Size
                Data Recording and Analysis',
            'Geo-Physical Borehole Survey > Well Logging > Logging Units' =>
                'Power Supply
                Data Acquisition System
                Portability
                Environmental Protection
                Compatibility with Logging Tools',
            'Geo-Physical Borehole Survey > Well Logging > Coils' =>
                'Length and Diameter
                Material
                Electrical Properties
                Durability
                Compatibility with Logging Equipment',
            'Geo-Physical Borehole Survey > Well Logging > Winches' =>
                'Cable Length and Strength
                Speed Control
                Load Capacity
                Power Source
                Durability and Environmental Resistance',
            'Geo-Physical Borehole Survey > Well Logging > Magnetic Logging' =>
                'Sensor Sensitivity
                Depth Capability
                Data Accuracy
                Environmental Suitability
                Data Processing Capabilities',
            'Geo-Physical Borehole Survey > Well Logging > Water Flow Survey' =>
                'Measurement Range
                Sensor Type
                Accuracy
                Data Transmission Method
                Compatibility with Well Conditions',
            'Geo-Physical Borehole Survey > Well Logging > Radiometrics' =>
                'Detector Type (e.g., Gamma Ray, Neutron)
                Measurement Range
                Data Accuracy
                Environmental Suitability
                Data Processing Capabilities',
            'Geo-Physical Borehole Survey > Well Logging > Flow Survey' =>
                'Flow Measurement Range
                Sensor Accuracy
                Data Recording and Transmission
                Compatibility with Fluid Types
                Environmental Suitability',
            'Geo-Physical Borehole Survey > Well Logging > Data Recording System' =>
                'Storage Capacity
                Data Transfer Speed
                Compatibility with Logging Tools
                Data Analysis Software
                Environmental Resistance',
            'Geo-Physical Borehole Survey > Well Logging > Seismic Measurements' =>
                'Sensor Sensitivity
                Depth and Range Capability
                Data Accuracy
                Processing Software
                Environmental Suitability',
            'Geo-Physical Borehole Survey > Well Logging > Photometry & Nephelometry' =>
                'Light Source Type
                Sensor Sensitivity
                Measurement Range
                Data Accuracy
                Compatibility with Fluid Types',
            'Geo-Physical Borehole Survey > Well Logging > Geoelectric Survey' =>
                'Electrode Type and Configuration
                Measurement Range
                Data Accuracy
                Depth Penetration
                Data Processing Capabilities',
            'Geo-Physical Borehole Survey > Sensors' =>
                'Type (e.g., Pressure, Temperature, Acoustic)
                Measurement Range
                Accuracy
                Environmental Suitability
                Connectivity with Data Systems',
            'Geo-Physical Borehole Survey > Cables' =>
                'Length and Diameter
                Material and Insulation
                Electrical Properties
                Strength and Durability
                Compatibility with Survey Equipment',
            'Geo-Physical Borehole Survey > Cameras' =>
                'Resolution and Image Quality
                Depth Capability
                Lighting Features
                Environmental Resistance
                Data Transmission Method',
            'Geo-Physical Borehole Survey > Data Recording System' =>
                'Storage Capacity
                Data Transfer Speed
                Compatibility with Survey Tools
                Data Analysis Software
                Environmental Resistance',
            'Geo-Physical Borehole Survey > Special Logging Equipment' =>
                'Specific to the Logging Type (e.g., Acoustic, Resistivity)
                Measurement Range and Accuracy
                Compatibility with Well Conditions
                Data Processing and Recording
                Operational Efficiency'
        ];
    }
}



