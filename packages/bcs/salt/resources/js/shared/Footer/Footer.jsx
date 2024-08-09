import { Row, Col } from "antd";
import React, { Suspense } from "react";

import { Container } from "../Container";
import { loadComponent, renderComponents } from '../../RenderComponents';

export default function Footer({footer}) {
  const components = footer.hasOwnProperty("components")
  ? footer.components?.map((item, indexItem) => {
        const component = loadComponent(item?.type);
        const theme=item.theme
        return { component, indexItem, theme };
    })
  : null;

  return (
    Object.keys(footer).length > 0 && (
      <footer
        className="footer backgroundImg"
        style={{ backgroundImage: `url(${footer.background?.data})` || null , backgroundColor: footer.background?.data || "transparent" }}
        id={footer.id}
      >
        <Container>
          <Suspense fallback={<h2>Loading</h2>}>
            <Row gutter={12} className="footer__container--row">
              {renderComponents(components).map((Component, key) => (
                <Col
                  xs={12}
                  sm={12}
                  md={12}
                  lg={6}
                  xl={6}
                  key={Component + key}
                  className="footer__container--col"
                >
                  {Component}
                </Col>

              ))}
            </Row>
          </Suspense>
        </Container>
      </footer>
    )
  );
}
