import clsx from "clsx";
import { Row, Col } from "antd";
import React, { Suspense } from "react";
import { usePage } from "@inertiajs/react";

import Card from "../Card/Card";
import { Container } from "../../Container";
import { SubHeading } from "../SubHeading";

export default function CardContentFour({ indexItem, background }) {
    const config = usePage().props.components[indexItem];

    return (
        <Suspense fallback={<h1>Loading Content Four...</h1>}>
            <div
                className="cardContentFour backgroundImg"
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
                                xxl: 20,
                                xl: 15,
                                lg: 30,
                                md: 15,
                                sm: 15,
                                xs: 15,
                            },
                            { xs: 10, sm: 20, md: 40, lg: 60, xl: 30 },
                        ]}
                        className={clsx("cardContentFour--row")}
                    >
                        {config.components?.length > 0 &&
                            config.components?.map((item, index) => (
                                <Col
                                    xs={12}
                                    sm={12}
                                    lg={12}
                                    xl={6}
                                    key={`${item.title} + ${
                                        item.img
                                    } + ${index} + ${Math.random()}`}
                                    className="custom-card-col"
                                >
                                    <Card
                                        ellipsis={8}
                                        config={config.components[index]}
                                    />
                                </Col>
                            ))}
                    </Row>
                </Container>
            </div>
        </Suspense>
    );
}
