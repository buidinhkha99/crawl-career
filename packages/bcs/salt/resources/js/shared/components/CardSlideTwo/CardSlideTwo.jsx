import clsx from "clsx";
import Slider from "react-slick";
import React, { Suspense } from "react";
import { usePage } from "@inertiajs/react";

import Card from "../Card/Card";
import { Container } from "../../Container";
import { SubHeading } from "../SubHeading";

const settings = {
    dots: true,
    arrows: false,
    adaptiveHeight: true,
    swipe: true,
    autoplay: true,
    infinite: true,
    speed: 500,
    slidesToShow: 2,
    slidesToScroll: 2,
    appendDots: (dots) => (
        <div className="absolute left-0">
            <ul style={{ margin: "0px" }}> {dots} </ul>
        </div>
    ),
    customPaging: (i) => <div className="button-slide" />,
    responsive: [
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        },
    ],
};

export default function CardSlideOne({ background, indexItem }) {
    const config = usePage().props.components[indexItem];

    return (
        <Suspense fallback={<h1>Loading Slide Two...</h1>}>
            <div
                className="home-banner card-slide cardSlideTwo backgroundImg"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <SubHeading background={background}>
                        {config.title}
                    </SubHeading>
                    <Slider {...settings}>
                        {config.components?.length > 0 &&
                            config.components.map((item, index) => (
                                <div key={item.title + item.img + index}>
                                    <Card
                                        ellipsis={8}
                                        config={config.components[index]}
                                        background={background}
                                    />
                                </div>
                            ))}
                    </Slider>
                </Container>
            </div>
        </Suspense>
    );
}
