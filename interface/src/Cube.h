//
//  Cube.h
//  interface
//
//  Created by Philip on 12/31/12.
//  Copyright (c) 2012 High Fidelity, Inc. All rights reserved.
//

#ifndef __interface__Cube__
#define __interface__Cube__

#include <glm/glm.hpp>
#include "Util.h"
#include "world.h"
#include "InterfaceConfig.h"
#include <iostream>

class VoxelSystem {
public:
    VoxelSystem(int num,
                   glm::vec3 box);
    void simulate(float deltaTime);
    void render();
private:
    struct Voxel {
        glm::vec3 color;
        bool hasChildren;
        Voxel * children;
    } *voxels;    
};


#endif