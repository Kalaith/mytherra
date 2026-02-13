import React from "react";
import { Landmark } from "../../entities/landmark";
import LandmarkList from "./LandmarkList";

interface RegionLandmarksTabProps {
  landmarks: Landmark[];
  onSelectLandmark?: (landmark: Landmark) => void;
}

const RegionLandmarksTab: React.FC<RegionLandmarksTabProps> = ({
  landmarks,
  onSelectLandmark,
}) => {
  return (
    <div>
      <LandmarkList landmarks={landmarks} onSelectLandmark={onSelectLandmark} />
    </div>
  );
};

export default RegionLandmarksTab;
