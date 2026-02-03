import Slider from "react-slick";
import { usePage } from "@inertiajs/react";
import React, { lazy, Suspense } from "react";

import { Container } from "../../Container";

const cardComponents = {
    cardSlide: lazy(() => import("../../../modules/Home/CardSlide/CardSlide")),
    cardTextOnly: lazy(() => import("../CardTextOnly/CardTextOnly")),
    cardImageOnly: lazy(() => import("../CardImageOnly/CardImageOnly")),
    cardContentExpand: lazy(() =>
        import("../CardContentExpand/CardContentExpand")
    ),
};

const settings = {
    dots: true,
    arrows: false,
    adaptiveHeight: true,
    swipe: true,
    autoplay: true,
    infinite: true,
    speed: 500,
    slidesToShow: 1,
    slidesToScroll: 1,
    appendDots: (dots) => (
        <div className="absolute left-0">
            <ul style={{ margin: "0px" }}> {dots} </ul>
        </div>
    ),
    customPaging: (i) => <div className="button-slide" />,
};

export default function CardSlideOne({ background, indexItem }) {
    const config = usePage().props.components[indexItem];

    return (
        <Suspense fallback={<h1>Loading Slide One...</h1>}>
            <div
                className="home-banner cardSlideOne backgroundImg"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <Slider {...settings}>
                        {config.components?.length > 0 &&
                            config.components.map((itemConfig, index) => {
                                const layoutKey = itemConfig.layout
                                    ? itemConfig.layout.split("-")
                                    : [];

                                let CardRender;
                                // Check if layout equal to only text or image
                                if (layoutKey.length === 2) {
                                    CardRender =
                                        layoutKey[1] === "info"
                                            ? cardComponents.cardTextOnly
                                            : cardComponents.cardImageOnly;
                                }

                                // Check if layout equal card horizontal with different scales
                                if (layoutKey.length === 3) {
                                    CardRender = layoutKey[
                                        layoutKey.length - 1
                                    ].includes("2")
                                        ? cardComponents.cardSlide
                                        : cardComponents.cardContentExpand;
                                }

                                return (
                                    <CardRender
                                        ellipsis={4}
                                        config={itemConfig}
                                        key={config.id + index}
                                    />
                                );
                            })}
                    </Slider>
                </Container>
            </div>
        </Suspense>
    );
}
