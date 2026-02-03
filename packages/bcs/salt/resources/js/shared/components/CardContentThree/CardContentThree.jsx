import clsx from "clsx";
import { Row, Col } from "antd";
import React, { Suspense } from "react";
import { usePage } from "@inertiajs/react";

import Card from "../Card/Card";
import { Container } from "../../Container";
import { SubHeading } from "../SubHeading";

export default function CardContentThree({ background, indexItem }) {
    const config = usePage().props.components[indexItem];

    return (
        <Suspense fallback={<h1>Loading Content Three...</h1>}>
            <div
                className="cardContentThree backgroundImg"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <SubHeading>{config.title}</SubHeading>
                    <Row
                        gutter={[
                            {
                                xxl: 105,
                                xl: 45,
                                lg: 30,
                                md: 15,
                                sm: 26,
                                xs: 26,
                            },
                            { xs: 10, sm: 20, md: 40, lg: 60, xl: 40 },
                        ]}
                        className={clsx("cardContentThree--row")}
                    >
                        {config.components?.length > 0 &&
                            config.components.map((item, index) => (
                                <Col
                                    xl={8}
                                    md={8}
                                    sm={8}
                                    xs={8}
                                    key={`${item.title} + ${
                                        item.img
                                    } + ${index} + ${Math.random()}`}
                                    className="custom-card-col"
                                >
                                    <Card
                                        ellipsis={8}
                                        config={config.components[index]}
                                        background={background}
                                    />
                                </Col>
                            ))}
                    </Row>
                </Container>
            </div>
        </Suspense>
    );
}
