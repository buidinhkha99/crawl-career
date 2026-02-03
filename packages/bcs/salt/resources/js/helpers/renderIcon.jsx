import React, { lazy } from "react";

const components = {
    alarmIcon: lazy(() => import("../icon/AlarmIcon")),
    facebookIcon: lazy(() => import("../icon/FacebookIcon")),
    alternateIcon: lazy(() => import("../icon/AlternateIcon")),
    twitterIcon: lazy(() => import("../icon/TwitterIcon")),
    youtubeIcon: lazy(() => import("../icon/YoutubeIcon")),
    figmaIcon: lazy(() => import("../icon/FigmaIcon")),
    apartmentIcon: lazy(() => import("../icon/ApartmentIcon")),
    menuIcon: lazy(() => import("../icon/MenuIcon")),
    infoIcon: lazy(() => import("../icon/InfoIcon")),
    searchIcon: lazy(() => import("../icon/SearchIcon")),
    clothesIcon: lazy(() => import("../icon/ClothesIcon")),
    devicesIcon: lazy(() => import("../icon/DevicesIcon")),
    cookingIcon: lazy(() => import("../icon/CookingIcon")),
};

export const renderIcon = (key, color, props) => {
    if (key === "") return null;
    const SpecificIcon = components[key];
    return <SpecificIcon {...props} color={color} />;
};
